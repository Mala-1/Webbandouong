<?php
require_once '../../includes/DBConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phải gửi bằng phương thức POST']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$import_order_id = $input['import_order_id'] ?? null;

if (!$import_order_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu nhập']);
    exit;
}

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    // 1. Lấy chi tiết phiếu nhập để rollback stock
    $stmtDetails = $pdo->prepare("SELECT product_id, packaging_option_id, quantity FROM import_order_details WHERE import_order_id = ?");
    $stmtDetails->execute([$import_order_id]);
    $details = $stmtDetails->fetchAll();

    foreach ($details as $detail) {
        $packaging_id = $detail['packaging_option_id'];
        $qty = (int) $detail['quantity'];

        // Lấy unit_quantity chính
        $stmtUnit = $pdo->prepare("SELECT product_id, unit_quantity FROM packaging_options WHERE packaging_option_id = ?");
        $stmtUnit->execute([$packaging_id]);
        $main = $stmtUnit->fetch();
        $main_unit = (int) preg_replace('/\D/', '', $main['unit_quantity'] ?? 1);
        $product_id = $main['product_id'];

        // Trừ stock chính
        $stmtUpdateMain = $pdo->prepare("UPDATE packaging_options SET stock = GREATEST(stock - ?, 0) WHERE packaging_option_id = ?");
        $stmtUpdateMain->execute([$qty, $packaging_id]);

        // Update lại stock cho các packaging_option khác cùng product_id
        $stmtOthers = $pdo->prepare("SELECT packaging_option_id, unit_quantity FROM packaging_options WHERE product_id = ? AND packaging_option_id != ?");
        $stmtOthers->execute([$product_id, $packaging_id]);

        foreach ($stmtOthers->fetchAll() as $other) {
            $other_id = $other['packaging_option_id'];
            $other_unit = (int) preg_replace('/\D/', '', $other['unit_quantity']);

            if ($main_unit > 0 && $other_unit > 0) {
                $convertedQty = round($qty * ($other_unit / $main_unit));

                $stmtUpdateOther = $pdo->prepare("UPDATE packaging_options SET stock = GREATEST(stock - ?, 0) WHERE packaging_option_id = ?");
                $stmtUpdateOther->execute([$convertedQty, $other_id]);
            }
        }
    }

    // 2. Xoá chi tiết phiếu nhập
    $pdo->prepare("DELETE FROM import_order_details WHERE import_order_id = ?")->execute([$import_order_id]);

    // 3. Xoá phiếu nhập
    $pdo->prepare("DELETE FROM import_order WHERE import_order_id = ?")->execute([$import_order_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Đã xoá phiếu nhập thành công']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
