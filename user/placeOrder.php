<?php
session_start();
include '../includes/DBConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $payment_method_id = $_POST['payment_method_id'] ?? null; // Automatically set to NULL if not provided
    $grandTotal = $_SESSION['grandTotal'] ?? 0;

    if (!$user_id) {
        echo '<p>Không tìm thấy thông tin người dùng.</p>';
        exit;
    }

    $db = DBConnect::getInstance();

    try {
        // Bắt đầu giao dịch
        $db->beginTransaction();

        // Thêm đơn hàng vào bảng orders
        $query = "INSERT INTO orders (user_id, status, total_price, shipping_address, created_at, payment_method_id) VALUES (?, 'Chờ xử lý', ?, ?, NOW(), ?)";
        $db->execute($query, [$user_id, $grandTotal, $address, $payment_method_id]);

        // Lấy danh sách sản phẩm từ giỏ hàng
        $cartItems = $_SESSION['cart'] ?? [];

        // Lấy ID của đơn hàng vừa được tạo
        $orderId = $db->getConnection()->lastInsertId();

        // Thêm chi tiết đơn hàng vào bảng order_details
        $queryDetails = "INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
        foreach ($cartItems as $item) {
            try {
                $db->execute($queryDetails, [$orderId, $item['product_id'], $item['packaging_option_id'], $item['quantity'], $item['price']]);
            } catch (PDOException $e) {
                error_log("Lỗi khi thêm vào order_details: " . $e->getMessage());
                throw new Exception("Không thể thêm chi tiết đơn hàng. Vui lòng kiểm tra lại dữ liệu.");
            }
        }

        // Hoàn tất giao dịch
        $db->commit();

        echo '<p>Đơn hàng đã được tạo thành công.</p>';
    } catch (Exception $e) {
        // Hủy giao dịch nếu có lỗi
        $db->rollBack();
        echo '<p>Lỗi khi tạo đơn hàng: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
} else {
    echo '<p>Yêu cầu không hợp lệ.</p>';
    exit;
}