<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

header('Content-Type: application/json');

if (!in_array('edit', $_SESSION['permissions']['customers'] ?? [])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền thêm khách hàng']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$phone = $input['phone'] ?? '';
$address = $input['address'] ?? '';
$email = $input['email'] ?? '';

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ username và mật khẩu']);
    exit;
}

// Kiểm tra username đã tồn tại
$sqlCheck = "SELECT COUNT(*) as count FROM users WHERE username = ?";
$checkResult = $db->select($sqlCheck, [$username]);
if ($checkResult[0]['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Username đã tồn tại']);
    exit;
}

// Kiểm tra email đã tồn tại (nếu có)
if ($email) {
    $sqlCheckEmail = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $checkResultEmail = $db->select($sqlCheckEmail, [$email]);
    if ($checkResultEmail[0]['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Email đã tồn tại']);
        exit;
    }
}

// Mã hóa mật khẩu (nếu cần)
// $password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, phone, address, email, role_id) VALUES (?, ?, ?, ?, ?, 1)";
$result = $db->execute($sql, [$username, $password, $phone, $address, $email]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm khách hàng']);
}
?>