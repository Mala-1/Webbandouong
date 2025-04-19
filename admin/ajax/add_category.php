<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = $_POST;

// Lấy dữ liệu từ form
$name = trim($data['category-name'] ?? '');
$image = $_FILES['category-image'] ?? null;

// Kiểm tra dữ liệu hợp lệ
if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên thể loại']);
    exit;
}
if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn hình ảnh thể loại hợp lệ']);
    exit;
}

// Xử lý tải lên hình ảnh
$imagePath = '../../assets/images/theloai/' . basename($image['name']);
move_uploaded_file($image['tmp_name'], $imagePath);

// Thêm vào CSDL
try {
    $sql = "INSERT INTO categories (name, image, is_deleted) VALUES (?, ?, 0)";
    $db->execute($sql, [$name, basename($image['name'])]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm thể loại: ' . $e->getMessage()]);
}
?>