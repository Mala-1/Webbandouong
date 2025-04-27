<?php
require_once('../../includes/DBConnect.php');
header('Content-Type: application/json');

// Kết nối DB
$db = DBConnect::getInstance();
$conn = $db->getConnection();

// Kiểm tra method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

// Lấy dữ liệu từ form
$user_id = intval($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');

// Validate cơ bản
if ($user_id <= 0 || empty($username) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
    exit;
}

try {
    // Bắt đầu transaction
    $conn->beginTransaction();

    // Update thông tin cơ bản (Không có role_id ở khách hàng)
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ? AND role_id = 1 AND is_deleted = 0");
    $stmt->execute([$username, $email, $phone, $address, $user_id]);

    // Nếu có nhập mật khẩu mới -> update thêm
    if (!empty($new_password)) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmtPwd = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmtPwd->execute([$hashedPassword, $user_id]);
    }

    $conn->commit();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>
