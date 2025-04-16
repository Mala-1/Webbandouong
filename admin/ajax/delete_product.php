<?php
require_once '../../includes/DBConnect.php';
$db = DBConnect::getInstance();

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;

if (!$productId || !is_numeric($productId)) {
    echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
    exit;
}

// Xoá mềm
$db->execute("UPDATE products SET is_deleted = 1 WHERE product_id = ?", [$productId]);

echo json_encode(['success' => true]);
