<?php
session_start();
include '../includes/DBConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$cartItems = $_SESSION['filteredCartItems'] ?? [];
$grandTotal = $_SESSION['grandTotal'] ?? 0;

$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$note = trim($_POST['note'] ?? '');
$payment_method_id = $_POST['payment_method_id'] ?? null;
$order_code = $_POST['order_code'] ?? null;

if (!$user_id || empty($cartItems) || $grandTotal <= 0) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng hoặc người dùng.']);
    exit;
}

try {
    $db = DBConnect::getInstance();
    $db->beginTransaction();

    // 1. Thêm đơn hàng
    $orderQuery = "INSERT INTO orders (user_id, status, total_price, shipping_address, created_at, payment_method_id, note, order_code)
                   VALUES (?, 'Chờ xử lý', ?, ?, NOW(), ?, ?, ?)";
    $db->execute($orderQuery, [$user_id, $grandTotal, $address, $payment_method_id, $note, $order_code]);

    $orderId = $db->getConnection()->lastInsertId();

    // 2. Thêm chi tiết đơn hàng
    $detailQuery = "INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price)
                    VALUES (?, ?, ?, ?, ?)";
    $cartDetailIdsToDelete = [];
    foreach ($cartItems as $item) {
        $db->execute($detailQuery, [
            $orderId,
            $item['product_id'],
            $item['packaging_option_id'],
            $item['quantity'],
            $item['price']
        ]);

        // Thu thập cart_detail_id để xóa sau
        if (isset($item['cart_detail_id'])) {
            $cartDetailIdsToDelete[] = $item['cart_detail_id'];
        }
    }

    // 3. Xoá các mục trong cart_details
    if (!empty($cartDetailIdsToDelete)) {
        $placeholders = implode(',', array_fill(0, count($cartDetailIdsToDelete), '?'));
        $deleteQuery = "DELETE FROM cart_details WHERE cart_detail_id IN ($placeholders)";
        $db->execute($deleteQuery, $cartDetailIdsToDelete);
    }

    $db->commit();

    // 4. Xoá session
    unset($_SESSION['filteredCartItems'], $_SESSION['grandTotal']);

    echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!', 'order_id' => $orderId]);
} catch (Exception $e) {
    $db->rollBack();
    error_log('Lỗi đặt hàng: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi khi xử lý đơn hàng.']);
}
