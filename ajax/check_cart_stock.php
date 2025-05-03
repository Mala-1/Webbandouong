<?php
// check_cart_stock.php
session_start();
require_once '../includes/DBConnect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit;
}

function formatProductName($packaging_type, $unit_quantity, $product_name)
{
    $packaging = trim($packaging_type . ' ' . $unit_quantity);
    // Loại trùng nếu packaging_type đã nằm trong unit_quantity
    if (stripos($unit_quantity, $packaging_type) !== false) {
        $packaging = $unit_quantity;
    }

    // Với unit_quantity là "1 lon", "1 chai", có thể tối giản
    if (preg_match('/^1\s+[[:alpha:]]+$/u', $unit_quantity)) {
        $packaging = $packaging_type; // chỉ in "Lon" hoặc "Chai"
    }

    return "{$packaging} {$product_name}";
}

$db = DBConnect::getInstance();
$user_id = $_SESSION['user_id'];

$cart = $db->selectOne("SELECT cart_id FROM cart WHERE user_id = ?", [$user_id]);
if (!$cart) {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
    exit;
}

$cartItems = $db->select("SELECT cd.packaging_option_id, cd.quantity, po.*, p.name as product_name
    FROM cart_details cd
    JOIN packaging_options po ON cd.packaging_option_id = po.packaging_option_id
    JOIN products p ON p.product_id = po.product_id
    WHERE cd.cart_id = ?", [$cart['cart_id']]);

function getUnitValue($unitStr)
{
    preg_match('/\d+/', $unitStr, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 1;
}

foreach ($cartItems as $item) {
    $packaging_option_id = $item['packaging_option_id'];
    $product_id = $item['product_id'];
    $quantity = $item['quantity'] * getUnitValue($item['unit_quantity']);

    // Lấy thông tin packaging_option đã chọn
    $packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);

    $product_id = $packaging_option['product_id'];


    // Lấy tất cả packaging_options của sản phẩm
    $packaging = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);

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

    $totalStock = getUnitValue($packaging_option['stock']);
    foreach ($packaging_stock as $p) {
        if ($p['unit_quantity'] > getUnitValue($packaging_option['unit_quantity'])) {
            $totalStock += $p['stock'] * $p['unit_quantity'] / getUnitValue($packaging_option['unit_quantity']);
        }
    }


    $check = false;

    // 3. Duyệt qua các packaging_option và trừ stock
    // Duyệt qua các packaging_option và trừ stock

    foreach ($packaging_stock as &$option) {

        $unit_quantity = $option['unit_quantity'];
        $stock = null;
        if ($option['stock'] == 0) continue;

        if ($unit_quantity <= $remaining_quantity && $unit_quantity >= getUnitValue($packaging_option['unit_quantity'])) {
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


            // nếu ko còn loại đóng gói để tách thì lấy lon lẻ
            if ($remaining_quantity > 0) {

                usort($packaging_stock, function ($a, $b) {
                    return $b['unit_quantity'] - $a['unit_quantity'];
                });
                foreach ($packaging_stock as &$option) {
                    $unit_quantity = $option['unit_quantity'];
                    if ($option['stock'] == 0) {
                        $i++;
                        continue;
                    }
                    if ($unit_quantity < getUnitValue($packaging_option['unit_quantity'])) {
                        $stock_used = intdiv($remaining_quantity, $unit_quantity);
                        if ($stock_used > $option['stock']) {
                            $stock_used = $option['stock'];
                        }
                        $option['stock'] -= $stock_used;
                        $remaining_quantity -= $stock_used * $unit_quantity;
                        $option['is_change'] = 1;

                        if ($remaining_quantity == 0) {
                            $is_empty = false;
                            break;
                        }
                    }
                }
            }



            if ($is_empty == true) {
                echo json_encode([
                    'success' => false,
                    'message' => "Không đủ số lượng cho sản phẩm: " . formatProductName($item['packaging_type'], $item['unit_quantity'], $item['product_name']) . ". Chỉ còn $totalStock sản phẩm."
                ]);
                exit;
            }
        }
    }
}

echo json_encode(['success' => true]);
