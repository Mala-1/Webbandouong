<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$permissions = $_SESSION['permissions'] ?? [];
$canWriteOrder = in_array('write', $permissions['Quản lý đơn hàng'] ?? []);
$canDeleteOrder = in_array('delete', $permissions['Quản lý đơn hàng'] ?? []);
$showActionColumn = $canWriteOrder || $canDeleteOrder;

$db = DBConnect::getInstance();

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_id = $_GET['search_id'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$status = $_GET['status'] ?? '';

$whereClauses = [];
$params = [];

// ✅ Luôn kiểm tra đơn hàng chưa bị xoá
$whereClauses[] = 'status != "cancelled"';

if ($search_id !== '') {
    $whereClauses[] = 'order_id = ?';
    $params[] = $search_id;
}

if ($price_min !== '') {
    $whereClauses[] = 'total_price >= ?';
    $params[] = $price_min;
}

if ($price_max !== '') {
    $whereClauses[] = 'total_price <= ?';
    $params[] = $price_max;
}

if ($status !== '') {
    $whereClauses[] = 'status = ?';
    $params[] = $status;
}

// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM orders $whereSql LIMIT $limit OFFSET $offset";
$orders = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalOrders = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalOrders / $limit);

// Khởi tạo phân trang
$pagination = new Pagination($totalOrders, $limit, $page);

ob_start();
foreach ($orders as $order): ?>
    <tr>
        <td><?= $order['order_id'] ?></td>
        <td><?= htmlspecialchars($order['user_id']) ?></td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td><?= number_format($order['total_price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= htmlspecialchars($order['shipping_address'] ?? 'Không có') ?></td>
        <td><?= htmlspecialchars($order['created_at']) ?></td>
        <?php if ($showActionColumn): ?>
            <td class="action-icons">
                <?php if ($canWriteOrder): ?>
                    <i class="fas fa-pen text-primary btn-edit-order me-3 fa-lg" style="cursor: pointer;"
                        data-id="<?= $order['order_id'] ?>"
                        data-status="<?= htmlspecialchars($order['status']) ?>"
                        data-total="<?= $order['total_price'] ?>"></i>
                <?php endif; ?>

                <?php if ($canDeleteOrder): ?>
                    <i class="fas fa-trash fa-lg text-danger btn-delete-order" style="cursor: pointer;"
                        data-id="<?= $order['order_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaDonHang"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$orderHtml = ob_get_clean();

echo $orderHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'orderpage') : '');