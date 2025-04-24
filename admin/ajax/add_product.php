<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$conn = $db->getConnection();

try {
    $name = $_POST['name'] ?? '';
    $price = 0;
    $size = $_POST['size'] ?? null;
    $category = $_POST['category'] ?? null;
    $brand = $_POST['brand'] ?? null;
    $description = $_POST['description'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $unit = $_POST['unit'] ?? '';

    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Thiếu tên sản phẩm.']);
        exit;
    } else if (!$category) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thể loại.']);
        exit;
    } else if (!$brand) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thương hiệu.']);
        exit;
    }

    // Thêm sản phẩm
    $productInsertSQL = "INSERT INTO products (name, price, category_id, brand_id, size,description, origin)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
    $db->execute($productInsertSQL, [$name, $price, $category, $brand, $size, $description, $origin]);
    $productId = $db->getConnection()->lastInsertId();

    // ✅ Hàm xử lý upload ảnh từ 1 input
    function handleImageUpload($fieldName, $productId, $uploadDir, $db)
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

    // ✅ 1. Lưu ảnh từ cả 2 input
    $uploadDir = "../../assets/images/SanPham/";
    handleImageUpload('images_hidden', $productId, $uploadDir, $db);
    handleImageUpload('images_outside', $productId, $uploadDir, $db);

    // ✅ 2. Lưu packaging_options
    $unit = trim($_POST['unit'] ?? '');

    if (!empty($unit)) {
        $packagingType = $unit;
        $unitQuantity = '1 ' . $unit; // vd: "1 hộp"

        $db->execute("INSERT INTO packaging_options (product_id, packaging_type, unit_quantity, image) 
                  VALUES (?, ?, ?, ?)", [$productId, $packagingType, $unitQuantity, null]);
    }

    $packaging_names = $_POST['packaging_name'] ?? [];
    $unit_quantities = $_POST['unit_quantity'] ?? [];
    $packaging_images = $_FILES['packaging_image'] ?? [];


    for ($i = 0; $i < count($packaging_names); $i++) {
        $name = $packaging_names[$i] ?? '';
        $unitQty = intval($unit_quantities[$i] ?? 1) . ' ' .  $unit; // số lượng lon

        $imageName = null;

        if (!empty($packaging_images['tmp_name'][$i]) && is_uploaded_file($packaging_images['tmp_name'][$i])) {
            $imageName = basename($packaging_images['name'][$i]);
            move_uploaded_file($packaging_images['tmp_name'][$i], $uploadDir . $imageName);
        }

        $db->execute("INSERT INTO packaging_options (product_id, packaging_type, unit_quantity, image) 
                      VALUES (?, ?, ?, ?)", [$productId, $name, $unitQty, $imageName]);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
