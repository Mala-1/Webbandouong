<?php
session_start();
require_once '../includes/DBConnect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$packaging_option_id = $data['packaging_option_id'] ?? 0;
$quan = $data['quantity'] ?? 0;
$price = $data['price'] ?? 0;
$user_id = $_SESSION['user_id'];

if ($packaging_option_id <= 0 || $quan <= 0 || $price <= 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$db = DBConnect::getInstance();
$conn = $db->getConnection();

// Trích số lượng từ chuỗi đơn vị (ví dụ "6 lon" => 6)
function getUnitValue($unitStr)
{
    preg_match('/\d+/', $unitStr, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 1;
}

// Lấy thông tin packaging_option đã chọn
$packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);

$product_id = $packaging_option['product_id'];


// Lấy tất cả packaging_options của sản phẩm
$packaging = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);


$quantity = $quan * getUnitValue($packaging_option['unit_quantity']);

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
foreach($packaging_stock as $p) {
    if($p['unit_quantity'] > getUnitValue($packaging_option['unit_quantity'])) {
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
            echo json_encode(["success" => false, "message" => "Không đủ số lượng. Chỉ còn $totalStock sản phẩm trong kho"]);
            exit;
        }
    }
}



// 1. Kiểm tra giỏ hàng đã tồn tại
$cart = $db->selectOne("SELECT * FROM cart WHERE user_id = ?", [$user_id]);

if (!$cart) {
    // 2. Tạo mới giỏ hàng nếu chưa có
    $db->execute("INSERT INTO cart (user_id, created_at) VALUES (?, NOW())", [$user_id]);
    // Lấy cart_id vừa tạo
    $cart_id = $conn->lastInsertId();
} else {
    $cart_id = $cart['cart_id'];
}

// 3. Kiểm tra sản phẩm đã có trong cart_detail chưa
$detail = $db->selectOne(
    "SELECT * FROM cart_details WHERE cart_id = ? AND packaging_option_id = ?",
    [$cart_id, $packaging_option_id]
);

if ($detail) {
    // 4. Cập nhật số lượng và total_price nếu đã có
    $newQuantity = $detail['quantity'] + $quan;
    $totalPrice = $newQuantity * $price;
    
    $db->execute(
        "UPDATE cart_details SET quantity = ?, price = ?, total_price = ? WHERE cart_id = ? AND packaging_option_id = ?",
        [$newQuantity, $price, $totalPrice, $cart_id, $packaging_option_id]
    );
} else {
    // 5. Thêm mới vào cart_detail
    $totalPrice = $quan * $price;
    $db->execute(
        "INSERT INTO cart_details (cart_id, packaging_option_id, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)",
        [$cart_id, $packaging_option_id, $quan, $price, $totalPrice]
    );
}

echo json_encode(['success' => true]);
