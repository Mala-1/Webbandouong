<?php
require_once '../../includes/DBConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phải gửi bằng phương thức POST']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$import_order_id = $input['import_order_id'] ?? null;

if (!$import_order_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu nhập']);
    exit;
}

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    // 1. Xoá chi tiết phiếu nhập
    $db->execute("DELETE FROM import_order_details WHERE import_order_id = ?", [$import_order_id]);

    // 2. Xoá phiếu nhập
    $db->execute("DELETE FROM import_order WHERE import_order_id = ?", [$import_order_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Đã xoá phiếu nhập và chi tiết thành công']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
