<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

header('Content-Type: application/json');

if (!in_array('edit', $_SESSION['permissions']['customers'] ?? [])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền chỉnh sửa khách hàng']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? '';
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$phone = $input['phone'] ?? '';
$address = $input['address'] ?? '';
$email = $input['email'] ?? '';

if (!$user_id || !$username) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ username']);
    exit;
}

// Kiểm tra username đã tồn tại (ngoại trừ user_id hiện tại)
$sqlCheck = "SELECT COUNT(*) as count FROM users WHERE username = ? AND user_id != ?";
$checkResult = $db->select($sqlCheck, [$username, $user_id]);
if ($checkResult[0]['count'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Username đã tồn tại']);
    exit;
}

// Kiểm tra email đã tồn tại (ngoại trừ user_id hiện tại, nếu có email)
if ($email) {
    $sqlCheckEmail = "SELECT COUNT(*) as count FROM users WHERE email = ? AND user_id != ?";
    $checkResultEmail = $db->select($sqlCheckEmail, [$email, $user_id]);
    if ($checkResultEmail[0]['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Email đã tồn tại']);
        exit;
    }
}

if ($password) {
    // $password = password_hash($password, PASSWORD_DEFAULT); // Mã hóa nếu cần
    $sql = "UPDATE users SET username = ?, password = ?, phone = ?, address = ?, email = ? WHERE user_id = ?";
    $result = $db->execute($sql, [$username, $password, $phone, $address, $email, $user_id]);
} else {
    $sql = "UPDATE users SET username = ?, phone = ?, address = ?, email = ? WHERE user_id = ?";
    $result = $db->execute($sql, [$username, $phone, $address, $email, $user_id]);
}

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật khách hàng']);
}
?>