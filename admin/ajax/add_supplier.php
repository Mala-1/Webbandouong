<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = json_decode(file_get_contents("php://input"), true);

// Lấy dữ liệu từ form
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$address = trim($data['address'] ?? '');

// Kiểm tra dữ liệu hợp lệ
if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên nhà cung cấp']);
    exit;
}
else if($email === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email']);
    exit;
}
else if($address === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập địa chỉ']);
    exit;
}

try {
    $sql = "INSERT INTO supplier (name, email, address, is_deleted) VALUES (?, ?, ?, 0)";
    $result = $db->execute($sql, [$name, $email, $address]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm nhà cung cấp: ' . $e->getMessage()]);
}
