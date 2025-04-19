<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = json_decode(file_get_contents('php://input'), true);

$categoryId = $data['category_id'] ?? null;

// Kiểm tra ID hợp lệ
if (!$categoryId || !is_numeric($categoryId)) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

try {
    // Xoá mềm
    $db->execute("UPDATE categories SET is_deleted = 1 WHERE category_id = ?", [$categoryId]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xoá thể loại: ' . $e->getMessage()]);
}
?>