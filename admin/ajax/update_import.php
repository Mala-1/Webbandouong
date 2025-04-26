<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

// Lấy dữ liệu từ POST
$import_order_id = $_POST['import_order_id'] ?? null;
$supplier_id = $_POST['supplier_id'] ?? null;
$user_id = $_POST['user_id'] ?? null;
$import_date = $_POST['import_date'] ?? null;
$product_ids = $_POST['product_id'] ?? [];
$packaging_options = $_POST['packaging_option'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$prices = $_POST['price'] ?? [];

if (!$import_order_id || !$supplier_id || !$user_id || !$import_date) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Cập nhật thông tin phiếu nhập
    $db->execute("UPDATE import_order SET supplier_id = ?, user_id = ?, created_at = ? WHERE import_order_id = ?", [
        $supplier_id, $user_id, $import_date, $import_order_id
    ]);

    // Xóa toàn bộ chi tiết cũ
    $db->execute("DELETE FROM import_order_details WHERE import_order_id = ?", [$import_order_id]);

    $total_price = 0;

    // Thêm lại chi tiết mới
    foreach ($product_ids as $i => $product_id) {
        $packaging_id = $packaging_options[$i];
        $quantity = (int)$quantities[$i];
        $raw_price = (float)str_replace(',', '', $prices[$i]);
        $line_total = $quantity * $raw_price;

        $db->execute("INSERT INTO import_order_details (import_order_id, product_id, quantity, price, total_price, packaging_option_id) VALUES (?, ?, ?, ?, ?, ?)", [
            $import_order_id, $product_id, $quantity, $raw_price, $line_total, $packaging_id
        ]);

        $total_price += $line_total;
    }

    // Cập nhật tổng tiền vào phiếu nhập
    $db->execute("UPDATE import_order SET total_price = ? WHERE import_order_id = ?", [$total_price, $import_order_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Cập nhật phiếu nhập thành công']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
