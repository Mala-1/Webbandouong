<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$db = DBConnect::getInstance();

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;
$params = [];

// 🔥 Luôn lọc người dùng có `role_id = 1`
$whereSql = "WHERE role_id = 1";

if (!empty($search)) {
    $whereSql .= " AND username LIKE ?";
    $params[] = "%" . $search . "%";
}

// 🔥 Truy vấn danh sách người dùng với phân trang
$sql = "SELECT SQL_CALC_FOUND_ROWS user_id, username, email, address FROM users $whereSql ORDER BY username ASC LIMIT $limit OFFSET $offset";
$users = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalUsers = $totalResult[0]['FOUND_ROWS()'];
$totalPages = ceil($totalUsers / $limit);

// 🔥 Khởi tạo phân trang
$pagination = new Pagination($totalUsers, $limit, $page);

ob_start();
foreach ($users as $user): ?>
    <tr data-id="<?= $user['user_id'] ?>" data-username="<?= $user['username'] ?>">
        <td><?= $user['user_id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><button class="btn btn-outline-primary select-user">Chọn</button></td>
    </tr>
<?php endforeach;

$userHtml = ob_get_clean();

// Trả về JSON
echo json_encode([
    "users" => $userHtml,
    "pagination" => $totalPages > 1 ? $pagination->render([], 'userpage') : ""
]);