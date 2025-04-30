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

    if (!$order_id || empty($details)) {
        echo json_encode(["success" => false, "message" => "Thiếu dữ liệu cập nhật đơn hàng. "]);
        exit;
    }

    $pdo = $db->getConnection();
    $pdo->beginTransaction();

    try {
        // Lấy chi tiết đơn hàng cũ
        $oldDetails = $db->select('SELECT * FROM order_details WHERE order_id = ?', [$order_id]);

        // Khôi phục stock từ chi tiết cũ
        foreach ($oldDetails as $old) {
            restoreStock($db, $old['product_id'], $old['packaging_option_id'], $old['quantity']);
        }

        // Xóa chi tiết cũ
        $db->execute('DELETE FROM order_details WHERE order_id = ?', [$order_id]);

        $total_price = 0;
        $all_packaging_stock = [];

        foreach ($details as &$item) {
            $product_id = $item['product_id'];
            $packaging_option_id = $item['packaging_option_id'];
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];
            $total_price += $quantity * $price;

            // Lấy danh sách packaging_options
            $packaging = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);
            $packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);
            $quantity *= getUnitValue($packaging_option['unit_quantity']);

            $packaging_stock = [];
            foreach ($packaging as $option) {
                $packaging_stock[] = [
                    'packaging_option_id' => $option['packaging_option_id'],
                    'unit_quantity' => getUnitValue($option['unit_quantity']),
                    'stock' => (int)$option['stock'],
                    'is_change' => 0,
                ];
            }
            usort($packaging_stock, fn($a, $b) => $b['unit_quantity'] - $a['unit_quantity']);

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

            if ($remaining_quantity > 0 && !$check) {
                $is_empty = true;
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
                            if ($packaging_stock[$j]['packaging_option_id'] == $packaging_option['packaging_option_id'] && $remaining_quantity <= $packaging_stock[$j]['stock'] * $packaging_stock[$j]['unit_quantity']) {
                                $packaging_stock[$j]['stock'] -= $remaining_quantity / getUnitValue($packaging_option['unit_quantity']);
                                $is_empty = false;
                                break;
                            } else {
                                $packaging_stock[$j]['stock']--;
                            }
                        }
                        break;
                    }
                    $i++;
                }

                if ($remaining_quantity > 0) {
                    usort($packaging_stock, fn($a, $b) => $b['unit_quantity'] - $a['unit_quantity']);
                    foreach ($packaging_stock as &$option) {
                        if ($option['stock'] == 0) continue;
                        if ($option['unit_quantity'] < getUnitValue($packaging_option['unit_quantity'])) {
                            $stock_used = intdiv($remaining_quantity, $option['unit_quantity']);
                            $stock_used = min($stock_used, $option['stock']);
                            $option['stock'] -= $stock_used;
                            $remaining_quantity -= $stock_used * $option['unit_quantity'];
                            $option['is_change'] = 1;
                            if ($remaining_quantity == 0) {
                                $is_empty = false;
                                break;
                            }
                        }
                    }
                }

                if ($is_empty) {
                    throw new Exception("Không đủ số lượng sản phẩm {$packaging_option['name']}");
                }
            }

            foreach ($packaging_stock as &$option) {
                if ($option['is_change']) {
                    $db->execute("UPDATE packaging_options SET stock = ? WHERE packaging_option_id = ?", [$option['stock'], $option['packaging_option_id']]);
                }
            }

            $db->execute("INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price) VALUES (?, ?, ?, ?, ?)", [
                $order_id, $product_id, $packaging_option_id, $item['quantity'], $item['price']
            ]);
        }

        $db->execute("UPDATE orders SET total_price = ?, status = ?, shipping_address = ?, payment_method_id = ?, note = ? WHERE order_id = ?", [
            $total_price, $data['status'], $data['shipping_address'], $data['payment_method_id'], $data['note'], $order_id
        ]);

        $pdo->commit();
        echo json_encode(["success" => true, "message" => "Cập nhật đơn hàng thành công!"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

function restoreStock($db, $product_id, $packaging_option_id, $quantity)
{
    $packaging_option = $db->selectOne('SELECT * FROM packaging_options WHERE packaging_option_id = ?', [$packaging_option_id]);
    if (!$packaging_option) return;

    $quantityRestore = $quantity * getUnitValue($packaging_option['unit_quantity']);
    $packagings = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);

    usort($packagings, fn($a, $b) => getUnitValue($b['unit_quantity']) - getUnitValue($a['unit_quantity']));

    foreach ($packagings as $pack) {
        $unit = getUnitValue($pack['unit_quantity']);
        if ($unit <= 0) continue;

        $canAdd = intdiv($quantityRestore, $unit);
        if ($canAdd > 0) {
            $db->execute('UPDATE packaging_options SET stock = stock + ? WHERE packaging_option_id = ?', [$canAdd, $pack['packaging_option_id']]);
            $quantityRestore -= $canAdd * $unit;
        }

        if ($quantityRestore <= 0) break;
    }
}
