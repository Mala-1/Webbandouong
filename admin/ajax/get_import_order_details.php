<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu nhập']);
    exit;
}

try {
    
    // 1. Lấy thông tin phiếu nhập (gồm mã NCC, tên NCC, người nhập, ngày nhập)
    $receiptInfo = $db->selectOne("
        SELECT io.import_order_id, io.supplier_id, s.name AS supplier_name, io.user_id, io.created_at
        FROM import_order io
        LEFT JOIN supplier s ON io.supplier_id = s.supplier_id
        WHERE io.import_order_id = ?
    ", [$orderId]);

    if (!$receiptInfo) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy phiếu nhập']);
        exit;
    }

    // 2. Lấy danh sách packaging_option_id đã dùng trong phiếu nhập
    $detailRows = $db->select("
        SELECT packaging_option_id 
        FROM import_order_details
        WHERE import_order_id = ?
    ", [$orderId]);


    $packagingIds = array_column($detailRows, 'packaging_option_id');

    if (empty($packagingIds)) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm nào trong phiếu nhập']);
        exit;
    }

    // 3. Tạo placeholders cho IN (...)
    $placeholders = implode(',', array_fill(0, count($packagingIds), '?'));

    // 4. Truy vấn thông tin sản phẩm tương ứng
    $sql = "
        SELECT
            iod.product_id,
            p.name AS product_name,
            p.size,
            iod.packaging_option_id,
            po.packaging_type,
            po.unit_quantity,
            iod.quantity,
            iod.price,
            (iod.quantity * iod.price) AS total,
            COALESCE(
                po.image,
                (
                    SELECT pi.image 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image ASC 
                    LIMIT 1
                )
            ) AS image
        FROM import_order_details iod
        INNER JOIN products p ON iod.product_id = p.product_id
        INNER JOIN packaging_options po ON iod.packaging_option_id = po.packaging_option_id
        WHERE iod.import_order_id = ?
    ";
    $products = $db->select($sql, [$orderId]);


    // 5. Trả kết quả
    echo json_encode([
        'success' => true,
        'receipt' => $receiptInfo,
        'products' => $products,
        'orderId' => $orderId
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
