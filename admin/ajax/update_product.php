<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

try {
    $db = DBConnect::getInstance();
    $conn = $db->getConnection();

    $product_id = $_POST['product_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $size = $_POST['size'] ?? '';
    $category = $_POST['category'] ?? null;
    $brand = $_POST['brand'] ?? null;
    $description = $_POST['description'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $unit = $_POST['unit'] ?? '';

    if (!$product_id || !$name || !$category || !$brand) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
        exit;
    }

    // ✅ Cập nhật thông tin sản phẩm
    $updateSQL = "UPDATE products 
                  SET name = ?, size = ?, category_id = ?, brand_id = ?, description = ?, origin = ?
                  WHERE product_id = ?";
    $db->execute($updateSQL, [$name, $size, $category, $brand, $description, $origin, $product_id]);

    // ✅ So sánh ảnh cũ: xoá ảnh nào không còn trong form
    $oldImagesInDB = $db->select("SELECT image FROM product_images WHERE product_id = ?", [$product_id]);
    $oldImagesInDB = array_column($oldImagesInDB, 'image');

    $oldImagesFromForm = $_POST['old_images'] ?? [];
    $imagesToDelete = array_diff($oldImagesInDB, $oldImagesFromForm);

    foreach ($imagesToDelete as $img) {
        $db->execute("DELETE FROM product_images WHERE product_id = ? AND image = ?", [$product_id, $img]);
        $filePath = "../../assets/images/SanPham/" . $img;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // ✅ Upload ảnh mới
    $uploadDir = "../../assets/images/SanPham/";

    function handleImageUploadUpdate($fieldName, $productId, $uploadDir, $db)
    {
        if (!empty($_FILES[$fieldName]['tmp_name'])) {
            foreach ($_FILES[$fieldName]['tmp_name'] as $key => $tmpName) {
                if (is_uploaded_file($tmpName)) {
                    $filename = basename($_FILES[$fieldName]['name'][$key]);
                    move_uploaded_file($tmpName, $uploadDir . $filename);
                    $db->execute("INSERT INTO product_images (product_id, image) VALUES (?, ?)", [$productId, $filename]);
                }
            }
        }
    }

    handleImageUploadUpdate('images_hidden', $product_id, $uploadDir, $db);
    handleImageUploadUpdate('images_outside', $product_id, $uploadDir, $db);

    // ✅ Cập nhật hoặc thêm mới packaging_options
    $packaging_ids = $_POST['packaging_option_id'] ?? [];
    $packaging_names = $_POST['packaging_name'] ?? [];
    $unit_quantities = $_POST['unit_quantity'] ?? [];
    $packaging_images = $_FILES['packaging_image'] ?? [];

    for ($i = 0; $i < count($packaging_names); $i++) {
        $packaging_id = $packaging_ids[$i] ?? null;
        $packName = trim($packaging_names[$i] ?? '');
        $unitQty = trim($unit_quantities[$i] ?? '');
        $imageName = null;

        if (!empty($packaging_images['tmp_name'][$i]) && is_uploaded_file($packaging_images['tmp_name'][$i])) {
            $imageName = basename($packaging_images['name'][$i]);
            move_uploaded_file($packaging_images['tmp_name'][$i], $uploadDir . $imageName);
        }

        if (!empty($packName) && is_numeric($unitQty)) {
            if ($packaging_id) {
                // ✅ UPDATE nếu có ID
                $sql = "UPDATE packaging_options 
                        SET packaging_type = ?, unit_quantity = ?, image = COALESCE(?, image)
                        WHERE packaging_option_id = ? AND product_id = ?";
                $db->execute($sql, [$packName, $unitQty, $imageName, $packaging_id, $product_id]);
            } else {
                // ✅ INSERT nếu chưa có
                $sql = "INSERT INTO packaging_options (product_id, packaging_type, unit_quantity, image)
                        VALUES (?, ?, ?, ?)";
                $db->execute($sql, [$product_id, $packName, $unitQty, $imageName]);
            }
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi: ' . $e->getMessage(),
        'line' => $e->getLine()
    ]);
}
