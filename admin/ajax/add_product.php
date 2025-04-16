<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$conn = $db->getConnection();

try {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? null;
    $brand = $_POST['brand'] ?? null;
    $description = $_POST['description'] ?? '';
    $origin = $_POST['origin'] ?? '';
    $unit = $_POST['unit'] ?? '';

    if (!$name || !$price || !$category) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
        exit;
    }

    // Thêm sản phẩm
    $productInsertSQL = "INSERT INTO products (name, price, category_id, brand_id, description, origin)
                     VALUES (?, ?, ?, ?, ?, ?)";
    $db->execute($productInsertSQL, [$name, $price, $category, $brand, $description, $origin]);
    $productId = $db->getConnection()->lastInsertId();

    // ✅ 1. Lưu ảnh chính (product_images)
    if (!empty($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if (is_uploaded_file($tmpName)) {
                $filename = basename($_FILES['images']['name'][$key]);

                move_uploaded_file($tmpName, "../../assets/images/SanPham/" . $filename);

                $db->execute("INSERT INTO product_images (product_id, image) VALUES (?, ?)", [$productId, $filename]);
            }
        }
    }

    // ✅ 2. Lưu packaging_options
    $packaging_names = $_POST['packaging_name'] ?? [];
    $unit_quantities = $_POST['unit_quantity'] ?? [];

    if (!empty($packaging_names) && is_array($packaging_names)) {
        foreach ($packaging_names as $i => $type) {
            $unitQty = $unit_quantities[$i] ?? 1;

            // xử lý ảnh đóng gói
            $filename = null;
            if (isset($_FILES['packaging_image']['tmp_name'][$i]) && is_uploaded_file($_FILES['packaging_image']['tmp_name'][$i])) {
                $filename = uniqid('pack_') . '_' . basename($_FILES['packaging_image']['name'][$i]);
                move_uploaded_file($_FILES['packaging_image']['tmp_name'][$i], "../../assets/images/SanPham/" . $filename);
            }

            $stmt = $conn->prepare("INSERT INTO packaging_options (product_id, packaging_type, unit_quantity, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$productId, $type, $unitQty, $filename]);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
