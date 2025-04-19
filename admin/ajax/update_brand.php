<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$data = $_POST;

$brandId = $data['brand_id'] ?? null;
$name = trim($data['brand-name'] ?? '');
$image = $_FILES['brand-image'] ?? null;

// Kiểm tra dữ liệu hợp lệ
if (!$brandId || !$name) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    // Nếu có tải lên hình ảnh mới
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $imagePath = '../../assets/images/Brand/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
        $db->execute("UPDATE brand SET name = ?, image = ? WHERE brand_id = ?", [$name, basename($image['name']), $brandId]);
    } else {
        // Cập nhật chỉ tên thương hiệu nếu không có ảnh mới
        $db->execute("UPDATE brand SET name = ? WHERE brand_id = ?", [$name, $brandId]);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật thương hiệu: ' . $e->getMessage()]);
}
?>