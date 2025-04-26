<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$role_id = $_POST['role_id'] ?? null;

if (!$role_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu role_id']);
    exit;
}

try {
    $db = DBConnect::getInstance();
    $db->execute("UPDATE roles SET is_deleted = 1 WHERE role_id = ?", [$role_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
