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
    $user_id = $_POST['user_id'] ?? null;
    $shipping_address = $_POST['shipping_address'] ?? null;
    $payment_method_id = $_POST['payment_method_id'] ?? null;
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Chờ xử lý';
    $details = json_decode($_POST['details'] ?? '[]', true);

    if (!$user_id || !$shipping_address || !$payment_method_id || empty($details)) {
        echo json_encode(["success" => false, "message" => "Thiếu thông tin đơn hàng hoặc chi tiết."]);
        exit;
    }

    $total_price = 0;
    $order_details = [];
    $all_packaging_stock = [];

    // Chuẩn bị danh sách packaging theo product_id (chỉ 1 lần)
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
    $o = -1;

    foreach ($details as $item) {
        $o++;
        
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

            $unit_quantity = $option['unit_quantity'];
            if ($unit_quantity <= $remaining_quantity && $unit_quantity >= getUnitValue($packaging_option['unit_quantity'])) {
                $stock_used = intdiv($remaining_quantity, $unit_quantity);
                $stock_used = min($stock_used, $option['stock']);

                $option['stock'] -= $stock_used;
                $remaining_quantity -= $stock_used * $unit_quantity;
                $option['is_change'] = 1;

                if ($remaining_quantity == 0) {
                    $check = true;
                    break;
                }
            }
        }
        unset($option); // 🧼 Bắt buộc sau foreach có &

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
                    $option['stock'] -= 1;
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
                            $remaining_quantity = 0;
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
            unset($option); // 🧼

           

            // fallback tách ra lon lẻ
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
                
                unset($option); // 🧼
            }

            if ($is_empty) {
                echo json_encode(["success" => false, "message" => "Không đủ số lượng sản phẩm {$packaging_option['name']}"]);
                exit;
            }
        }

        unset($packaging_stock); // Cẩn thận
        
    }
    unset($item); // 🧼
    

    // Tạo đơn hàng
    $sql = "INSERT INTO orders (user_id, status, shipping_address, note, created_at, payment_method_id)
            VALUES (?, ?, ?, ?, NOW(), ?)";
    $params = [$user_id, $status, $shipping_address, $note, $payment_method_id];
    $success = $db->execute($sql, $params);

    if (!$success) {
        echo json_encode(["success" => false, "message" => "Không thể tạo đơn hàng."]);
        exit;
    }

    $pdo = $db->getConnection();
    $order_id = $pdo->lastInsertId();

    foreach ($order_details as $detail) {
        $db->execute("INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price)
                      VALUES (?, ?, ?, ?, ?)", [
            $order_id,
            $detail['product_id'],
            $detail['packaging_option_id'],
            $detail['quantity'],
            $detail['price']
        ]);
    }

    // Cập nhật stock + log
    foreach ($all_packaging_stock as $product_id => $packaging_list) {
        
        foreach ($packaging_list as $option) {
            if (!empty($option['is_change'])) {
                $db->execute(
                    "UPDATE packaging_options SET stock = ? WHERE packaging_option_id = ?",
                    [$option['stock'], $option['packaging_option_id']]
                );

                $delta_quantity = $option['original_stock'] - $option['stock'];
                if ($delta_quantity !== 0) {
                    // Nếu giảm tồn kho → log dương | Nếu tăng tồn kho (do tách) → log âm
                    $db->execute(
                        "INSERT INTO order_stock_log (order_id, packaging_option_id, quantity) VALUES (?, ?, ?)",
                        [$order_id, $option['packaging_option_id'], $delta_quantity]
                    );
                }
            }
        }
    }

    $db->execute("UPDATE orders SET total_price = ? WHERE order_id = ?", [$total_price, $order_id]);

    echo json_encode(["success" => true, "message" => "Đơn hàng đã được thêm thành công."]);
}
