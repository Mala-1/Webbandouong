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
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$status = $_GET['status'] ?? '';

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
if ($from_date !== '') {
    $whereClauses[] = 'DATE(io.created_at) >= ?';
    $params[] = $from_date;
}
if ($to_date !== '') {
    $whereClauses[] = 'DATE(io.created_at) <= ?';
    $params[] = $to_date;
}
if ($status !== '') {
    $whereClauses[] = 'io.status = ?';
    $params[] = $status;
}


// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS io.*, s.name AS supplier_name, u.username 
        FROM import_order io
        LEFT JOIN supplier s ON io.supplier_id = s.supplier_id
        LEFT JOIN users u ON io.user_id = u.user_id
        $whereSql 
        ORDER BY io.created_at DESC
        LIMIT $limit OFFSET $offset";

$receipts = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalReceipts = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalReceipts / $limit);

// Khởi tạo phân trang
$pagination = new Pagination($totalReceipts, $limit, $page);


ob_start();
foreach ($receipts as $receipt):
    $isDone = ($receipt['status'] == 'Đã xác nhận');
    $classStatus = $isDone ? 'text-success' : 'text-warning border border-warning p-1 rounded'; ?>
    <tr>
        <td><?= htmlspecialchars($receipt['import_order_id']) ?></td>
        <td><?= htmlspecialchars($receipt['supplier_name']) ?></td>
        <td><?= htmlspecialchars($receipt['username']) ?></td>
        <td><?= number_format($receipt['total_price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= htmlspecialchars($receipt['created_at']) ?></td>
        <td class="fw-bold rounded">
            <?php if (!$isDone): ?>
                <div class="<?= $classStatus ?>" onclick="openConfirmModal(<?= $receipt['import_order_id'] ?>)">
                    <i class="fa-solid fa-hourglass-half"></i>
                    <span style="cursor: pointer;">
                        <?= htmlspecialchars($receipt['status']) ?>
                    </span>
                </div>
            <?php else: ?>
                <span class="<?= $classStatus ?>">
                    <?= htmlspecialchars($receipt['status']) ?>
                </span>
            <?php endif; ?>
        </td>
        <?php if ($canWriteReceipt || $canDeleteReceipt || $canReadReceipt): ?>
            <td class="action-icons">
                <?php if ($canReadReceipt): ?>
                    <i class="fas fa-eye text-info btn-view-receipt fa-lg" style="cursor: pointer;"
                        data-id="<?= $receipt['import_order_id'] ?>"
                        title="Xem chi tiết phiếu nhập"
                        data-bs-toggle="modal" data-bs-target="#viewReceiptModal"></i>
                <?php endif; ?>
                <?php if ($canWriteReceipt && !$isDone): ?>
                    <i class="fas fa-pen text-primary btn-edit-receipt fa-lg ms-3" style="cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#editReceiptModal"
                        data-id="<?= $receipt['import_order_id'] ?>"
                        data-supplier="<?= htmlspecialchars($receipt['supplier_id']) ?>"
                        data-user="<?= htmlspecialchars($receipt['user_id']) ?>"
                        data-price="<?= htmlspecialchars($receipt['total_price']) ?>">
                    </i>
                <?php endif; ?>

                <?php if ($canDeleteReceipt && $isDone == 0): ?>
                    <i class="fas fa-trash fa-lg text-danger btn-delete-receipt ms-3" style="cursor: pointer;"
                        data-id="<?= $receipt['import_order_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaPhieuNhap"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$receiptHtml = ob_get_clean();

echo $receiptHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'receiptpage') : '');
