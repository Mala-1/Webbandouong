<?php
session_start();
require_once '../../includes/DBConnect.php';

$db = DBConnect::getInstance();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = $_POST['order_id'] ?? null;

    if (!$order_id) {
        echo json_encode(["success" => false, "message" => "Thiếu mã đơn hàng để xoá."]);
        exit;
    }

    $pdo = $db->getConnection();
    $pdo->beginTransaction();

    try {
        // 1. Lấy trạng thái hiện tại của đơn hàng
        $order = $db->selectOne("SELECT status FROM orders WHERE order_id = ?", [$order_id]);

        if (!$order) {
            throw new Exception("Không tìm thấy đơn hàng.");
        }

        $isNotCancelled = ($order['status'] !== 'Đã hủy');

        // 2. Nếu đơn chưa hủy thì cộng lại tồn kho từ log
        if ($isNotCancelled) {
            $logs = $db->select(
                "SELECT packaging_option_id, quantity FROM order_stock_log WHERE order_id = ?",
                [$order_id]
            );

            foreach ($logs as $log) {
                $db->execute(
                    "UPDATE packaging_options 
                     SET stock = stock + ? 
                     WHERE packaging_option_id = ?",
                    [$log['quantity'], $log['packaging_option_id']]
                );
            }
        }

        // 3. Xoá log
        $db->execute("DELETE FROM order_stock_log WHERE order_id = ?", [$order_id]);

        // 4. Xoá chi tiết đơn hàng
        $db->execute("DELETE FROM order_details WHERE order_id = ?", [$order_id]);

        // 5. Xoá đơn hàng
        $db->execute("DELETE FROM orders WHERE order_id = ?", [$order_id]);

        $pdo->commit();

        echo json_encode(["success" => true, "message" => "Đã xoá đơn hàng thành công."]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
