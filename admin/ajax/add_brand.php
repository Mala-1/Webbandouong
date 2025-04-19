<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = $_POST;

// Lấy dữ liệu từ form
$name = trim($data['brand-name'] ?? '');
$image = $_FILES['brand-image'] ?? null;

// Kiểm tra dữ liệu hợp lệ
if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên thương hiệu']);
    exit;
}
if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn hình ảnh thương hiệu hợp lệ']);
    exit;
}

// Xử lý tải lên hình ảnh
$imagePath = '../../assets/images/Brand/' . basename($image['name']);
move_uploaded_file($image['tmp_name'], $imagePath);

// Thêm vào CSDL
try {
    $sql = "INSERT INTO brand (name, image, is_deleted) VALUES (?, ?, 0)";
    $db->execute($sql, [$name, basename($image['name'])]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm thương hiệu: ' . $e->getMessage()]);
}
?>