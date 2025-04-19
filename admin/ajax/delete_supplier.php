<?php
require_once '../../includes/DBConnect.php';
$db = DBConnect::getInstance();

$data = json_decode(file_get_contents('php://input'), true);
$supplierId = $data['supplier_id'] ?? null;


if (!$supplierId || !is_numeric($supplierId)) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

// Xoá mềm
$db->execute("UPDATE supplier SET is_deleted = 1 WHERE supplier_id = ?", [$supplierId]);

echo json_encode(['success' => true]);
