<?php
require_once '../../includes/DBConnect.php'; // Đường dẫn tùy vào dự án của bạn

header('Content-Type: application/json');

// Kết nối DB
$db = DBConnect::getInstance();
$conn = $db->getConnection();

// Check method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit;
}

// Lấy dữ liệu từ POST
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$role_id = intval($_POST['role_id'] ?? 0);

// Validate
if (empty($username) || empty($password) || empty($email) || $role_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
    exit;
}

// Kiểm tra username/email đã tồn tại chưa (nếu cần)
$stmtCheck = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
$stmtCheck->execute([$username, $email]);
$count = $stmtCheck->fetchColumn();
if ($count > 0) {
    echo json_encode(['success' => false, 'message' => 'Username hoặc Email đã tồn tại']);
    exit;
}

// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Thêm vào database
$stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, address, role_id) 
                        VALUES (?, ?, ?, ?, ?, ?)");
try {
    $stmt->execute([$username, $hashedPassword, $email, $phone, $address, $role_id]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
