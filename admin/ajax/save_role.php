<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

// Kiểm tra phương thức
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phải gửi yêu cầu bằng POST']);
    exit;
}

// Lấy dữ liệu
$role_name = trim($_POST['role_name'] ?? '');

if (empty($role_name)) {
    echo json_encode(['success' => false, 'message' => 'Tên nhóm không được để trống']);
    exit;
}

try {
    $db = DBConnect::getInstance();
    $pdo = $db->getConnection();

    // Kiểm tra nhóm đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE name = ?");
    $stmt->execute([$role_name]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên nhóm đã tồn tại']);
        exit;
    }

    // Thêm nhóm mới
    $stmt = $pdo->prepare("INSERT INTO roles (name) VALUES (?)");
    $stmt->execute([$role_name]);

    echo json_encode(['success' => true, 'message' => 'Đã thêm nhóm thành công']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>
