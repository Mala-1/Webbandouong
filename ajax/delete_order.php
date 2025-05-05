<?php
session_start();
require_once '../includes/DBConnect.php';

$db = DBConnect::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $order_id = $_GET['order_id'] ?? null;

    if (!$order_id) {
        header("Location: ../user/Cart.php");
        exit;
    }

    $pdo = $db->getConnection();
    $pdo->beginTransaction();

    try {
        // 1. Truy xuất log tồn kho
        $logs = $db->select(
            "SELECT packaging_option_id, quantity FROM order_stock_log WHERE order_id = ?",
            [$order_id]
        );

        if (empty($logs)) {
            throw new Exception("Không có log tồn kho để hoàn tác.");
        }

        // 2. Hoàn tác tồn kho
        foreach ($logs as $log) {
            $db->execute(
                "UPDATE packaging_options
                 SET stock = stock + ? 
                 WHERE packaging_option_id = ?",
                [$log['quantity'], $log['packaging_option_id']]
            );
        }

        // 3. Cập nhật trạng thái đơn hàng thành "Đã hủy"
        $db->execute("UPDATE orders SET status = 'Đã hủy' WHERE order_id = ?", [$order_id]);

        $pdo->commit();

        header("Location: ../user/Cart.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: ../user/Cart.php");
        exit;
    }
}
