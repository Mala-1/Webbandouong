<?php
session_start();
require_once '../../includes/DBConnect.php';

$db = DBConnect::getInstance();

function getUnitValue($unitStr)
{
    preg_match('/\d+/', $unitStr, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 1;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $order_id = $data['order_id'] ?? null;
    $details = $data['details'] ?? [];
    $status = $data['status'] ?? 'Chờ xử lý';
    $shipping_address = $data['shipping_address'] ?? '';
    $payment_method_id = $data['payment_method_id'] ?? null;
    $note = $data['note'] ?? '';

    if (!$order_id || empty($details)) {
        echo json_encode(["success" => false, "message" => "Thiếu dữ liệu cập nhật đơn hàng."]);
        exit;
    }

    $pdo = $db->getConnection();
    $pdo->beginTransaction();

    try {
        // Khôi phục tồn kho cũ
        $oldDetails = $db->select("SELECT * FROM order_details WHERE order_id = ?", [$order_id]);
        foreach ($oldDetails as $old) {
            $packaging_option = $db->selectOne("SELECT * FROM packaging_options WHERE packaging_option_id = ?", [$old['packaging_option_id']]);
            $restore_qty = $old['quantity'] * getUnitValue($packaging_option['unit_quantity']);
            $packagings = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$old['product_id']]);
            usort($packagings, fn($a, $b) => getUnitValue($b['unit_quantity']) - getUnitValue($a['unit_quantity']));

            foreach ($packagings as $pack) {
                $unit = getUnitValue($pack['unit_quantity']);
                if ($unit <= 0) continue;

                $canAdd = intdiv($restore_qty, $unit);
                if ($canAdd > 0) {
                    $db->execute('UPDATE packaging_options SET stock = stock + ? WHERE packaging_option_id = ?', [$canAdd, $pack['packaging_option_id']]);
                    $restore_qty -= $canAdd * $unit;
                }
                if ($restore_qty <= 0) break;
            }
        }

        // Xoá chi tiết cũ và log cũ
        $db->execute("DELETE FROM order_details WHERE order_id = ?", [$order_id]);
        $db->execute("DELETE FROM order_stock_log WHERE order_id = ?", [$order_id]);

        $total_price = 0;
        $order_details = [];
        $all_packaging_stock = [];

        $product_ids = array_unique(array_column($details, 'product_id'));

        foreach ($product_ids as $product_id) {
            $packaging = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);
            $all_packaging_stock[$product_id] = [];
            foreach ($packaging as $option) {
                $all_packaging_stock[$product_id][] = [
                    'packaging_option_id' => $option['packaging_option_id'],
                    'unit_quantity' => getUnitValue($option['unit_quantity']),
                    'stock' => (int)$option['stock'],
                    'original_stock' => (int)$option['stock'],
                    'is_change' => 0,
                ];
            }
            usort($all_packaging_stock[$product_id], fn($a, $b) => $b['unit_quantity'] - $a['unit_quantity']);
        }

        foreach ($details as $item) {
            $product_id = $item['product_id'];
            $packaging_option_id = $item['packaging_option_id'];
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];
            $total_price += $quantity * $price;

            $order_details[] = [
                'product_id' => $product_id,
                'packaging_option_id' => $packaging_option_id,
                'quantity' => $quantity,
                'price' => $price
            ];

            $packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);
            $quantity *= getUnitValue($packaging_option['unit_quantity']);

            $packaging_stock = &$all_packaging_stock[$product_id];
            $remaining_quantity = $quantity;
            $check = false;

            foreach ($packaging_stock as &$option) {
                if ($option['stock'] == 0) continue;
                if ($option['unit_quantity'] <= $remaining_quantity && $option['unit_quantity'] >= getUnitValue($packaging_option['unit_quantity'])) {
                    $stock_used = intdiv($remaining_quantity, $option['unit_quantity']);
                    $stock_used = min($stock_used, $option['stock']);
                    $option['stock'] -= $stock_used;
                    $remaining_quantity -= $stock_used * $option['unit_quantity'];
                    $option['is_change'] = 1;
                    if ($remaining_quantity == 0) {
                        $check = true;
                        break;
                    }
                }
            }
            unset($option);

            if ($remaining_quantity > 0 && !$check) {
                usort($packaging_stock, fn($a, $b) => $a['unit_quantity'] <=> $b['unit_quantity']);
                $i = 0;
                foreach ($packaging_stock as &$option) {
                    if ($option['stock'] == 0) {
                        $i++;
                        continue;
                    }
                    if ($option['unit_quantity'] > getUnitValue($packaging_option['unit_quantity'])) {
                        $pre_unit_quantity = $option['unit_quantity'];
                        $option['stock']--;
                        $option['is_change'] = 1;
                        for ($j = $i - 1; $j >= 0; $j--) {
                            $packaging_stock[$j]['stock'] += floor($pre_unit_quantity / $packaging_stock[$j]['unit_quantity']);
                            $pre_unit_quantity = $packaging_stock[$j]['unit_quantity'];
                            $packaging_stock[$j]['is_change'] = 1;
                            if (
                                $packaging_stock[$j]['packaging_option_id'] == $packaging_option_id &&
                                $remaining_quantity <= $packaging_stock[$j]['stock'] * $packaging_stock[$j]['unit_quantity']
                            ) {
                                $packaging_stock[$j]['stock'] -= $remaining_quantity / getUnitValue($packaging_option['unit_quantity']);
                                break 2;
                            } else {
                                $packaging_stock[$j]['stock']--;
                            }
                        }
                    }
                    $i++;
                }
            }
            unset($option);
        }
        unset($item);

        foreach ($order_details as $detail) {
            $db->execute("INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price) VALUES (?, ?, ?, ?, ?)", [
                $order_id,
                $detail['product_id'],
                $detail['packaging_option_id'],
                $detail['quantity'],
                $detail['price']
            ]);
        }

        foreach ($all_packaging_stock as $product_id => $packaging_list) {
            foreach ($packaging_list as $option) {
                if (!empty($option['is_change'])) {
                    $db->execute("UPDATE packaging_options SET stock = ? WHERE packaging_option_id = ?", [$option['stock'], $option['packaging_option_id']]);
                    $delta = $option['original_stock'] - $option['stock'];
                    if ($delta !== 0) {
                        $db->execute("INSERT INTO order_stock_log (order_id, packaging_option_id, quantity) VALUES (?, ?, ?)", [$order_id, $option['packaging_option_id'], $delta]);
                    }
                }
            }
        }

        $db->execute("UPDATE orders SET total_price = ?, status = ?, shipping_address = ?, payment_method_id = ?, note = ? WHERE order_id = ?", [
            $total_price,
            $status,
            $shipping_address,
            $payment_method_id,
            $note,
            $order_id
        ]);

        $pdo->commit();
        echo json_encode(["success" => true, "message" => "Cập nhật đơn hàng thành công!"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
