<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = $_POST;

$categoryId = $data['category_id'] ?? null;
$name = trim($data['category-name'] ?? '');
$image = $_FILES['category-image'] ?? null;

// Kiểm tra dữ liệu hợp lệ
if (!$categoryId || !$name) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    // Nếu có tải lên hình ảnh mới
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $imagePath = '../../assets/images/theloai/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
        $db->execute("UPDATE categories SET name = ?, image = ? WHERE category_id = ?", [$name, basename($image['name']), $categoryId]);
    } else {
        // Cập nhật chỉ tên thể loại nếu không có ảnh mới
        $db->execute("UPDATE categories SET name = ? WHERE category_id = ?", [$name, $categoryId]);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật thể loại: ' . $e->getMessage()]);
}
?>