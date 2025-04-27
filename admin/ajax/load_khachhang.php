<?php
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$db = DBConnect::getInstance();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$permissions = $_SESSION['permissions'] ?? [];
$canWrite = in_array('write', $permissions['Quản lý khách hàng'] ?? []);
$canDelete = in_array('delete', $permissions['Quản lý khách hàng'] ?? []);

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_name = $_GET['search_name'] ?? '';
$whereClauses = [];
$params = [];

// Chỉ lấy khách hàng
$whereClauses[] = 'role_id = 1 AND is_deleted = 0';

if ($search_name !== '') {
    $whereClauses[] = 'username LIKE ?';
    $params[] = '%' . $search_name . '%';
}

$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users $whereSql LIMIT $limit OFFSET $offset";
$customers = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalCustomers = $totalResult[0]['FOUND_ROWS()'];

$totalPages = ceil($totalCustomers / $limit);
$pagination = new Pagination($totalCustomers, $limit, $page);

ob_start();
foreach ($customers as $customer):
?>
    <tr>
        <td><?= $customer['user_id'] ?></td>
        <td><?= htmlspecialchars($customer['username']) ?></td>
        <td><?= htmlspecialchars($customer['email']) ?></td>
        <td><?= htmlspecialchars($customer['phone']) ?></td>
        <td><?= htmlspecialchars($customer['address']) ?></td>
        <?php if ($canWrite || $canDelete): ?>
            <td class="action-icons">
                <?php if ($canWrite): ?>
                    <i class="fas fa-pen text-primary btn-edit-customer me-2 fa-lg" style="cursor: pointer;"
                        data-id="<?= $customer['user_id'] ?>"
                        data-username="<?= htmlspecialchars($customer['username']) ?>"
                        data-email="<?= htmlspecialchars($customer['email']) ?>"
                        data-phone="<?= htmlspecialchars($customer['phone']) ?>"
                        data-address="<?= htmlspecialchars($customer['address']) ?>"
                        data-bs-toggle="modal" data-bs-target="#editCustomerModal"></i>
                <?php endif; ?>
                <?php if ($canDelete): ?>
                    <i class="fas fa-trash text-danger btn-delete-customer fa-lg" style="cursor: pointer;"
                        data-id="<?= $customer['user_id'] ?>"
                        data-bs-toggle="modal" data-bs-target="#modalXoaCustomer"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$content = ob_get_clean();
echo $content . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'khachhangpage') : '');
?>
