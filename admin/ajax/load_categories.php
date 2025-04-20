<?php
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';
$db = DBConnect::getInstance();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$permissions = $_SESSION['permissions'] ?? [];
$canWrite = in_array('write', $permissions['Quản lý thể loại'] ?? []);
$canDelete = in_array('delete', $permissions['Quản lý thể loại'] ?? []);


$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_name = $_GET['search_name'] ?? '';

$whereClauses = [];
$params = [];

// ✅ Luôn kiểm tra thể loại chưa bị xoá
$whereClauses[] = 'is_deleted = 0';

if ($search_name !== '') {
    $whereClauses[] = 'name LIKE ?';
    $params[] = '%' . $search_name . '%';
}

// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories $whereSql LIMIT $limit OFFSET $offset";
$categories = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalCategories = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalCategories / $limit);

// Khởi tạo phân trang
$pagination = new Pagination($totalCategories, $limit, $page);

ob_start();
foreach ($categories as $category): ?>
    <tr>
        <td><?= $category['category_id'] ?></td>
        <td><img src="../../assets/images/theloai/<?= $category['image'] ?>" width="50" alt="<?= $category['name'] ?>"></td>
        <td><?= htmlspecialchars($category['name']) ?></td>
        <?php if ($canWrite || $canDelete): ?>
            <td class="action-icons">
                <?php if ($canWrite): ?>
                    <i class="fas fa-pen text-primary btn-edit-category me-3 fa-lg" style="cursor: pointer;"
                        data-id="<?= $category['category_id'] ?>"
                        data-name="<?= htmlspecialchars($category['name']) ?>"
                        data-image="<?= htmlspecialchars($category['image']) ?>"></i>
                <?php endif; ?>

                <?php if ($canDelete): ?>
                    <i class="fas fa-trash fa-lg text-danger btn-delete-category" style="cursor: pointer;"
                        data-id="<?= $category['category_id'] ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#modalXoaCategory"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$categoryHtml = ob_get_clean();

echo $categoryHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'categorypage') : '');
