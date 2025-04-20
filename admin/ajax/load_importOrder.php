<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$permissions = $_SESSION['permissions'] ?? [];
$canWriteReceipt = in_array('write', $permissions['Quản lý đơn nhập'] ?? []);
$canDeleteReceipt = in_array('delete', $permissions['Quản lý đơn nhập'] ?? []);
$canReadReceipt = in_array('read', $permissions['Quản lý đơn nhập'] ?? []);

$db = DBConnect::getInstance();

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_id = $_GET['search_id'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';

$whereClauses = [];
$params = [];

if ($search_id !== '') {
    $whereClauses[] = 'io.import_order_id = ?';
    $params[] = $search_id;
}

if ($price_min !== '') {
    $whereClauses[] = 'io.total_price >= ?';
    $params[] = $price_min;
}

if ($price_max !== '') {
    $whereClauses[] = 'io.total_price <= ?';
    $params[] = $price_max;
}

// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS io.*, s.name AS supplier_name, u.username 
        FROM import_order io
        LEFT JOIN supplier s ON io.supplier_id = s.supplier_id
        LEFT JOIN users u ON io.user_id = u.user_id
        $whereSql LIMIT $limit OFFSET $offset";

$receipts = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalReceipts = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalReceipts / $limit);

// Khởi tạo phân trang
$pagination = new Pagination($totalReceipts, $limit, $page);

ob_start();
foreach ($receipts as $receipt): ?>
    <tr>
        <td><?= htmlspecialchars($receipt['import_order_id']) ?></td>
        <td><?= htmlspecialchars($receipt['supplier_id']) ?></td>
        <td><?= htmlspecialchars($receipt['user_id']) ?></td>
        <td><?= number_format($receipt['total_price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= htmlspecialchars($receipt['created_at']) ?></td>
        <?php if ($canWriteReceipt || $canDeleteReceipt || $canReadReceipt): ?>
            <td class="action-icons">
                <?php if ($canReadReceipt): ?>
                    <i class="fas fa-eye text-info btn-view-receipt me-3 fa-lg" style="cursor: pointer;"
                        data-id="<?= $receipt['import_order_id'] ?>"
                        title="Xem chi tiết phiếu nhập"
                        data-bs-toggle="modal" data-bs-target="#viewReceiptModal"></i>
                <?php endif; ?>
                <?php if ($canWriteReceipt): ?>
                    <i class="fas fa-pen text-primary btn-edit-receipt me-3 fa-lg" style="cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#editReceiptModal"
                        data-id="<?= $receipt['import_order_id'] ?>"
                        data-supplier="<?= htmlspecialchars($receipt['supplier_id']) ?>"
                        data-user="<?= htmlspecialchars($receipt['user_id']) ?>"
                        data-price="<?= htmlspecialchars($receipt['total_price']) ?>">
                    </i>
                <?php endif; ?>

                <?php if ($canDeleteReceipt): ?>
                    <i class="fas fa-trash fa-lg text-danger btn-delete-receipt" style="cursor: pointer;"
                        data-id="<?= $receipt['import_order_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaPhieuNhap"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$receiptHtml = ob_get_clean();

echo $receiptHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'receiptpage') : '');
