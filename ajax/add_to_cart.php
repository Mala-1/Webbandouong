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
$quantity = $data['quantity'] ?? 0;
$price = $data['price'] ?? 0;
$user_id = $_SESSION['user_id'];

if ($packaging_option_id <= 0 || $quantity <= 0 || $price <= 0) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$db = DBConnect::getInstance();
$conn = $db->getConnection();

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
    $newQuantity = $detail['quantity'] + $quantity;
    $totalPrice = $newQuantity * $price;

    $db->execute(
        "UPDATE cart_details SET quantity = ?, price = ?, total_price = ? WHERE cart_id = ? AND packaging_option_id = ?",
        [$newQuantity, $price, $totalPrice, $cart_id, $packaging_option_id]
    );
} else {
    // 5. Thêm mới vào cart_detail
    $totalPrice = $quantity * $price;

    $db->execute(
        "INSERT INTO cart_details (cart_id, packaging_option_id, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)",
        [$cart_id, $packaging_option_id, $quantity, $price, $totalPrice]
    );
}

echo json_encode(['success' => true]);
