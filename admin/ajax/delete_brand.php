<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = json_decode(file_get_contents('php://input'), true);

$brandId = $data['brand_id'] ?? null;

// Kiểm tra ID hợp lệ
if (!$brandId || !is_numeric($brandId)) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

try {
    // Xoá mềm
    $db->execute("UPDATE brand SET is_deleted = 1 WHERE brand_id = ?", [$brandId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xoá thương hiệu: ' . $e->getMessage()]);
}
?>