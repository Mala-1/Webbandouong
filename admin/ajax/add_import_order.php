<?php
require_once '../../includes/DBConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = DBConnect::getInstance();
    $pdo = $db->getConnection(); // dùng trực tiếp PDO

    $supplier_id = $_POST['supplier_id'] ?? null;
    session_start();
    $user_id = $_SESSION['admin_id'] ?? null;
    $import_date = $_POST['import_date'] ?? date('Y-m-d');

    $product_ids = $_POST['product_id'] ?? [];
    $packaging_ids = $_POST['packaging_option'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $prices = $_POST['price'] ?? [];

    if (!$supplier_id || !$user_id || empty($product_ids)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin phiếu nhập']);
        exit;
    }

    $total_price = 0;
    foreach ($quantities as $i => $qty) {
        $qty = (int)$qty;
        $price = floatval(str_replace(',', '', $prices[$i]));
        $total_price += $qty * $price;
    }

    try {
        $pdo->beginTransaction();

        // Insert phiếu nhập
        $stmt = $pdo->prepare("INSERT INTO import_order (supplier_id, user_id, total_price, created_at, status) VALUES (?, ?, ?, ?, 'Chờ xác nhận')");
        $stmt->execute([$supplier_id, $user_id, $total_price, $import_date]);
        $import_order_id = $pdo->lastInsertId();

        // Insert chi tiết phiếu nhập
        foreach ($product_ids as $i => $product_id) {
            $packaging_id = $packaging_ids[$i] ?? null;
            $quantity = (int)($quantities[$i] ?? 0);
            $price = floatval(str_replace(',', '', $prices[$i] ?? 0));
            $total = $quantity * $price;

            // Chỉ insert chi tiết, KHÔNG cập nhật stock hay giá
            $stmt = $pdo->prepare("INSERT INTO import_order_details 
                (import_order_id, product_id, quantity, price, total_price, packaging_option_id) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$import_order_id, $product_id, $quantity, $price, $total, $packaging_id]);
        }

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Thêm phiếu nhập thành công']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phải gửi bằng phương thức POST']);
}
