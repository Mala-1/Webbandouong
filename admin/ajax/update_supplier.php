<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$supplier_id = $data['supplier_id'] ?? null;
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$address = trim($data['address'] ?? '');

if (!$supplier_id || !$name || !$email || !$address) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$db = DBConnect::getInstance();
$sql = "UPDATE supplier SET name = ?, email = ?, address = ? WHERE supplier_id = ?";
$success = $db->execute($sql, [$name, $email, $address, $supplier_id]);

echo json_encode(['success' => $success]);
