<?php
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$db = DBConnect::getInstance();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$permissions = $_SESSION['permissions'] ?? [];
$canWrite = in_array('write', $permissions['Quản lý nhân viên'] ?? []);
$canDelete = in_array('delete', $permissions['Quản lý nhân viên'] ?? []);

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_name = $_GET['search_name'] ?? '';
$whereClauses = [];
$params = [];

$whereClauses[] = 'role_id NOT IN (1, 2)';

if ($search_name !== '') {
    $whereClauses[] = 'username LIKE ?';
    $params[] = '%' . $search_name . '%';
}

$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users $whereSql LIMIT $limit OFFSET $offset";
$users = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalUsers = $totalResult[0]['FOUND_ROWS()'];

$totalPages = ceil($totalUsers / $limit);
$pagination = new Pagination($totalUsers, $limit, $page);

ob_start();
foreach ($users as $user): ?>
    <tr>
        <td><?= $user['user_id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['phone']) ?></td>
        <td><?= htmlspecialchars($user['address']) ?></td>
        <td><?= htmlspecialchars($user['role_id']) ?></td>
        <?php if ($canWrite || $canDelete): ?>
            <td class="action-icons">
                <?php if ($canWrite): ?>
                    <i class="fas fa-pen text-primary btn-edit-user me-2 fa-lg" style="cursor: pointer;"
                        data-id="<?= $user['user_id'] ?>"
                        data-username="<?= htmlspecialchars($user['username']) ?>"
                        data-email="<?= htmlspecialchars($user['email']) ?>"
                        data-phone="<?= htmlspecialchars($user['phone']) ?>"
                        data-address="<?= htmlspecialchars($user['address']) ?>"
                        data-role="<?= htmlspecialchars($user['role_id']) ?>"
                        data-bs-toggle="modal" data-bs-target="#editUserModal"></i>
                <?php endif; ?>
                <?php if ($canDelete): ?>
                    <i class="fas fa-trash text-danger btn-delete-user fa-lg" style="cursor: pointer;"
                        data-id="<?= $user['user_id'] ?>"
                        data-bs-toggle="modal" data-bs-target="#modalXoaNhanVien"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$content = ob_get_clean();
echo $content . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'nhanvienpage') : '');
