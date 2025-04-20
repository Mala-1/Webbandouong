<?php
session_start();
require_once '../../includes/DBConnect.php';

$db = DBConnect::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $shipping_address = $_POST['shipping_address'] ?? null;
    $payment_method_id = $_POST['payment_method_id'] ?? null;
    $note = $_POST['note'] ?? '';
    $status = $_POST['status'] ?? 'Chờ xử lý';

    if (!$user_id || !$shipping_address || !$payment_method_id) {
        echo json_encode(["success" => false, "message" => "Vui lòng nhập đầy đủ thông tin!"]);
        exit;
    }

    // 1. Thêm đơn hàng
    $sql = "INSERT INTO orders (user_id, status, shipping_address, note, created_at, payment_method_id)
            VALUES (?, ?, ?, ?, NOW(), ?)";
    $params = [$user_id, $status, $shipping_address, $note, $payment_method_id];
    $success = $db->execute($sql, $params);

    if ($success) {
        $pdo = $db->getConnection(); // Lấy đối tượng PDO thực tế
        $order_id = $pdo->lastInsertId();

        // 2. Lấy thông tin đơn hàng vừa tạo
        $orderInfo = $db->select(
            "SELECT o.*, pm.name AS payment_method_name 
             FROM orders o
             LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
             WHERE o.order_id = ?",
            [$order_id]
        );

        if ($orderInfo && count($orderInfo) > 0) {
            $order = $orderInfo[0];
            echo json_encode([
                "success" => true,
                "message" => "Đơn hàng đã được thêm thành công!",
                "order_id" => $order['order_id'],
                "user_id" => $order['user_id'],
                "status" => $order['status'],
                "total_price" => $order['total_price'] ?? 0,
                "shipping_address" => $order['shipping_address'],
                "created_at" => $order['created_at'],
                "payment_method_name" => $order['payment_method_name'] ?? 'Không xác định'
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Không lấy được thông tin đơn hàng sau khi tạo."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Có lỗi xảy ra khi thêm đơn hàng!"]);
    }
}
