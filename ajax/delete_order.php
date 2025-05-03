<?php
session_start();
require_once '../includes/DBConnect.php';

if (!isset($_GET['order_id'])) {
    http_response_code(400);
    echo 'Thiếu mã đơn hàng.';
    exit;
}

$orderId = (int) $_GET['order_id'];
$db = DBConnect::getInstance();

try {
    $sql = "UPDATE orders SET status = 'Đã hủy' WHERE order_id = ?";
    $stmt = $db->getConnection()->prepare($sql);
    $stmt->execute([$orderId]);

    header("Location: ../user/cart.php"); // hoặc đổi thành trang phù hợp
    exit;
} catch (PDOException $e) {
    echo 'Lỗi khi cập nhật trạng thái đơn hàng: ' . $e->getMessage();
    exit;
}
?>
