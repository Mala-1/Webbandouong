<?php
session_start();
include '../includes/DBConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $payment_method_id = $_POST['payment_method_id'] ?? null;
    $grandTotal = $_SESSION['grandTotal'] ?? 0;

    $db = DBConnect::getInstance();

    try {
        // Insert new order into the orders table
        $query = "INSERT INTO orders (user_id, status, total_price, shipping_address, created_at, payment_method_id) VALUES (?, 'Chờ xử lý', ?, ?, NOW(), ?)";
        $db->execute($query, [$user_id, $grandTotal, $address, $payment_method_id]);

        // Insert order details
        $cartItems = $_SESSION['filteredCartItems'] ?? [];
        foreach ($cartItems as $item) {
            $query = "INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price) VALUES (LAST_INSERT_ID(), ?, ?, ?, ?)";
            $db->execute($query, [$item['product_id'], $item['packaging_option_id'], $item['quantity'], $item['price']]);
        }

        // Clear cart session data
        unset($_SESSION['cartItems'], $_SESSION['filteredCartItems'], $_SESSION['grandTotal']);

        echo json_encode(['success' => true, 'message' => 'Đơn hàng đã được tạo thành công.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}