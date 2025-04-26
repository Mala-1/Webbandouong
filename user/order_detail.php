<?php
session_start();
include '../includes/DBConnect.php';

// Kiểm tra xem order_id có được truyền qua query string không
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    echo '<p>Không tìm thấy mã đơn hàng.</p>';
    exit;
}

$db = DBConnect::getInstance();

try {
    // Lấy thông tin đơn hàng
    $orderQuery = "SELECT * FROM orders WHERE order_id = ?";
    $order = $db->selectOne($orderQuery, [$order_id]);

    if (!$order) {
        echo '<p>Đơn hàng không tồn tại.</p>';
        exit;
    }

    // Lấy chi tiết đơn hàng
    $detailsQuery = "SELECT od.*, p.name AS product_name FROM order_details od
                     JOIN products p ON od.product_id = p.product_id
                     WHERE od.order_id = ?";
    $orderDetails = $db->select($detailsQuery, [$order_id]);

    // Hiển thị thông tin đơn hàng
    echo '<h1>Chi tiết đơn hàng</h1>';
    echo '<p>Mã đơn hàng: ' . htmlspecialchars($order['order_id']) . '</p>';
    echo '<p>Ngày tạo: ' . htmlspecialchars($order['created_at']) . '</p>';
    echo '<p>Tổng tiền: ' . htmlspecialchars($order['total_price']) . ' VND</p>';

    // Hiển thị chi tiết sản phẩm
    echo '<h2>Sản phẩm</h2>';
    echo '<table border="1">';
    echo '<tr><th>Tên sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Tổng</th></tr>';
    foreach ($orderDetails as $detail) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($detail['product_name']) . '</td>';
        echo '<td>' . htmlspecialchars($detail['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($detail['price']) . ' VND</td>';
        echo '<td>' . htmlspecialchars($detail['quantity'] * $detail['price']) . ' VND</td>';
        echo '</tr>';
    }
    echo '</table>';
} catch (Exception $e) {
    echo '<p>Lỗi khi lấy thông tin đơn hàng: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>