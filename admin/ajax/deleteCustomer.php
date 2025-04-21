<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

header('Content-Type: application/json');

if (!in_array('edit', $_SESSION['permissions']['customers'] ?? [])) {
    echo json_encode(['success' => false, 'message' => 'Không có quyền xóa khách hàng']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? '';

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'ID khách hàng không hợp lệ']);
    exit;
}

$sql = "UPDATE users SET is_deleted = 1 WHERE user_id = ?";
$result = $db->execute($sql, [$user_id]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa khách hàng']);
}
?>