<?php
session_start();
require_once '../../includes/DBConnect.php';
$debug = '';

$db = DBConnect::getInstance();

// Trích số lượng từ chuỗi đơn vị (ví dụ "6 lon" => 6)
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

    

    // Kiểm tra tính hợp lệ của thông tin
    if (!$user_id || !$shipping_address || !$payment_method_id || empty($details)) {
        echo json_encode(["success" => false, "message" => "Thiếu thông tin đơn hàng hoặc chi tiết."]);
        exit;
    }



    
    $total_price = 0;

    // Mảng lưu chi tiết đơn hàng
    $order_details = [];
    $all_packaging_stock = [];

    // 2. Xử lý chi tiết đơn hàng
    foreach ($details as &$item) {
        $product_id = $item['product_id'];
        $packaging_option_id = $item['packaging_option_id'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];
        $total_price += $quantity * $price;

        // Tạo thông tin chi tiết đơn hàng để lưu vào mảng
        $order_details[] = [
            'product_id' => $product_id,
            'packaging_option_id' => $packaging_option_id,
            'quantity' => $quantity,
            'price' => $price
        ];

        // Lấy tất cả packaging_options của sản phẩm
        $packaging = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);


        // Lấy thông tin packaging_option đã chọn
        $packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);
        $quantity *= getUnitValue($packaging_option['unit_quantity']);

        // Tạo mảng để lưu thông tin về các packaging_option
        $packaging_stock = [];

        foreach ($packaging as $option) {
            $packaging_stock[] = [
                'packaging_option_id' => $option['packaging_option_id'],
                'unit_quantity' => getUnitValue($option['unit_quantity']),
                'stock' => (int)$option['stock'],
                'is_change' => 0, // Đánh dấu mặc định là không thay đổi
            ];
        }
        

        // Sắp xếp packaging_stock theo unit_quantity giảm dần
        usort($packaging_stock, function ($a, $b) {
            return $b['unit_quantity'] - $a['unit_quantity'];
        });
        // Tính toán số lượng còn thiếu
        $remaining_quantity = $quantity;


        $check = false;

        // 3. Duyệt qua các packaging_option và trừ stock
        // Duyệt qua các packaging_option và trừ stock
        foreach ($packaging_stock as &$option) {
            $unit_quantity = $option['unit_quantity'];
            $stock = null;
            if ($option['stock'] == 0) continue;
            if ($unit_quantity <= $remaining_quantity) {

                $stock_used = intdiv($remaining_quantity, $unit_quantity);
                if ($stock_used > $option['stock']) {
                    $stock_used = $option['stock'];
                }
                $option['stock'] -= $stock_used;
                $remaining_quantity -= $stock_used * $unit_quantity;
                $option['is_change'] = 1;

                if ($remaining_quantity == 0) {
                    $check = true;
                    break;
                }
            }
        }


        if ($remaining_quantity > 0) {


            if ($check == false) {
                $is_empty = true;
                usort($packaging_stock, function ($a, $b) {
                    return $a['unit_quantity'] <=> $b['unit_quantity'];
                });

                $i = 0;
                foreach ($packaging_stock as &$option) {
                    $unit_quantity = $option['unit_quantity'];
                    $stock = null;
                    if ($option['stock'] == 0) {
                        $i++;
                        continue;
                    }

                    if ($unit_quantity > getUnitValue($packaging_option['unit_quantity'])) {
                        $pre_unit_quantity = $option['unit_quantity'];
                        $option['stock'] -= 1;
                        $option['is_change'] = 1;
                        for ($j = $i - 1; $j >= 0; $j--) {
                            $packaging_stock[$j]['stock'] += floor($pre_unit_quantity / $packaging_stock[$j]['unit_quantity']);
                            $packaging_stock[$j]['is_change'] = 1;
                            if ($packaging_stock[$j]['packaging_option_id'] == $packaging_option['packaging_option_id'] && $remaining_quantity <= $packaging_stock[$j]['stock']) {
                                $packaging_stock[$j]['stock'] -= $remaining_quantity;
                                $packaging_stock[$j]['is_change'] = 1;
                                $is_empty = false;
                                break;
                            }
                        }
                        break;
                    }

                    $i++;
                }
                if ($is_empty == true) {
                    echo json_encode(["success" => false, "message" => "Không đủ số lượng sản phẩm {$packaging_option['name']} + {$debug}"]);
                    exit;
                }
            }
        }

        // Lưu tất cả packaging_stock vào mảng chung ngoài foreach
        foreach ($packaging_stock as &$option) {
            if($option['is_change'] == 1) {
                $all_packaging_stock[] = $option;

            }
        }

    }
    
    // 1. Tạo đơn hàng mới
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

    // Sau khi duyệt xong tất cả các chi tiết đơn hàng, lưu vào database
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

    // Cập nhật stock cho tất cả packaging_option nếu có thay đổi
    foreach ($all_packaging_stock as $option) {
        if ($option['is_change'] == 1) {
            $db->execute(
                "UPDATE packaging_options 
                 SET stock = ? 
                 WHERE packaging_option_id = ?",
                [$option['stock'], $option['packaging_option_id']]
            );
        }
    }

    // Cập nhật tổng giá sau khi tất cả chi tiết đơn hàng đã được xử lý
    $db->execute("UPDATE orders SET total_price = ? WHERE order_id = ?", [$total_price, $order_id]);

    echo json_encode(["success" => true, "message" => "Đơn hàng đã được thêm thành công."]);
}
