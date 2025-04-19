<?php
require_once '../includes/DBConnect.php';
header('Content-Type: application/json');

try {
    $db = DBConnect::getInstance();
    $conn = $db->getConnection();

    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? null;
    $cartDetailId = $data['cart_detail_id'] ?? null;
    $quantity = $data['quantity'] ?? null;

    if (!$action || !$cartDetailId || !is_numeric($cartDetailId)) {
        echo json_encode(['success' => false, 'message' => 'Thông tin không hợp lệ']);
        exit;
    }

    if ($action === 'update' && is_numeric($quantity) && $quantity > 0) {
        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $updateSQL = "UPDATE cart_details SET quantity = ? WHERE cart_detail_id = ?";
        $db->execute($updateSQL, [$quantity, $cartDetailId]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'delete') {
        // Xóa sản phẩm khỏi giỏ hàng
        $deleteSQL = "DELETE FROM cart_details WHERE cart_detail_id = ?";
        $db->execute($deleteSQL, [$cartDetailId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi: ' . $e->getMessage(),
        'line' => $e->getLine()
    ]);
}