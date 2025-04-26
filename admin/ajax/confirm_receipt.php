<?php
session_start();
require_once '../../includes/DBConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phải gửi bằng phương thức POST']);
    exit;
}

$import_order_id = $_POST['import_order_id'] ?? null;

if (!$import_order_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu nhập']);
    exit;
}

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    // 1. Cộng stock từ chi tiết phiếu nhập
    $details = $db->select("SELECT packaging_option_id, quantity FROM import_order_details WHERE import_order_id = ?", [$import_order_id]);

    foreach ($details as $detail) {
        $packaging_id = $detail['packaging_option_id'];
        $quantity = (int) $detail['quantity'];

        if ($packaging_id && $quantity > 0) {
            // Cộng stock
            $db->execute("UPDATE packaging_options SET stock = stock + ? WHERE packaging_option_id = ?", [$quantity, $packaging_id]);
        }
    }

    // 2. Cập nhật trạng thái phiếu nhập thành 'Đã xác nhận'
    $db->execute("UPDATE import_order SET status = 'Đã xác nhận' WHERE import_order_id = ?", [$import_order_id]);

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
