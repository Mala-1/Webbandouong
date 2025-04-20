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

if (!$import_order_id || !$supplier_id || !$user_id || !$import_date || empty($product_ids)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Cập nhật thông tin phiếu nhập
    $stmt = $pdo->prepare("UPDATE import_order SET supplier_id = ?, user_id = ?, created_at = ? WHERE import_order_id = ?");
    $stmt->execute([$supplier_id, $user_id, $import_date, $import_order_id]);

    // Xóa chi tiết cũ
    $stmt = $pdo->prepare("DELETE FROM import_order_details WHERE import_order_id = ?");
    $stmt->execute([$import_order_id]);

    // Lấy margin_percent
    $margin = $db->selectOne("SELECT margin_percent FROM profitmargin LIMIT 1")['margin_percent'] ?? 10;
    $margin_factor = (100 - $margin) / 100;

    $total_price = 0;

    // Thêm lại các dòng chi tiết
    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = $product_ids[$i];
        $packaging_id = $packaging_options[$i];
        $quantity = (int)$quantities[$i];
        $raw_price = (float)$prices[$i];
        $final_price = round($raw_price * $margin_factor);
        $line_total = $final_price * $quantity;

        $stmt = $pdo->prepare("INSERT INTO import_order_details (import_order_id, product_id, quantity, price, total_price, packaging_option_id)
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$import_order_id, $product_id, $quantity, $final_price, $line_total, $packaging_id]);

        $total_price += $line_total;

        // Cập nhật lại giá trong packaging_options
        $stmtUpdate = $pdo->prepare("UPDATE packaging_options SET price = ? WHERE packaging_option_id = ?");
        $stmtUpdate->execute([$final_price, $packaging_id]);
    }

    // Cập nhật tổng giá cho phiếu nhập
    $stmt = $pdo->prepare("UPDATE import_order SET total_price = ? WHERE import_order_id = ?");
    $stmt->execute([$total_price, $import_order_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Cập nhật phiếu nhập thành công']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
