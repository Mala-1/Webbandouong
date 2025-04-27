<?php
require_once('../../includes/DBConnect.php');
header('Content-Type: application/json');

// Kết nối DB
$db = DBConnect::getInstance();
$conn = $db->getConnection();

// Check method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

// Lấy dữ liệu user_id từ client gửi lên
$data = json_decode(file_get_contents("php://input"), true);
$user_id = intval($data['user_id'] ?? 0);

// Kiểm tra dữ liệu đầu vào
if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID nhân viên không hợp lệ']);
    exit;
}

try {
    // Xóa mềm: cập nhật is_deleted = 1
    $stmt = $conn->prepare("UPDATE users SET is_deleted = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy nhân viên để cập nhật']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>
