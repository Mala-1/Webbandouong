<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';


$permissions = $_SESSION['permissions'] ?? [];
$canWriteBrand = in_array('write', $permissions['Quản lý thương hiệu'] ?? []);
$canDeleteBrand = in_array('delete', $permissions['Quản lý thương hiệu'] ?? []);
$showActionColumn = $canWriteBrand || $canDeleteBrand;

$db = DBConnect::getInstance();

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_name = $_GET['search_name'] ?? '';

$whereClauses = [];
$params = [];

// ✅ Luôn kiểm tra thương hiệu chưa bị xoá
$whereClauses[] = 'is_deleted = 0';

if ($search_name !== '') {
    $whereClauses[] = 'name LIKE ?';
    $params[] = '%' . $search_name . '%';
}

// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM brand $whereSql LIMIT $limit OFFSET $offset";
$brands = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalBrand = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalBrand / $limit);

// Khởi tạo phân trang
$pagination = new Pagination($totalBrand, $limit, $page);

ob_start();
foreach ($brands as $brand): ?>
    <tr>
        <td><?= $brand['brand_id'] ?></td>
        <td><img src="../../assets/images/Brand/<?= $brand['image'] ?>" width="50" alt="<?= $brand['name'] ?>"></td>
        <td><?= htmlspecialchars($brand['name']) ?></td>
        <?php if ($showActionColumn): ?>
            <td class="action-icons">
                <?php if ($canWriteBrand): ?>
                    <i class="fas fa-pen text-primary btn-edit-brand me-3 fa-lg" style="cursor: pointer;"
                        data-id="<?= $brand['brand_id'] ?>"
                        data-name="<?= htmlspecialchars($brand['name']) ?>"
                        data-image="<?= htmlspecialchars($brand['image']) ?>"></i>
                <?php endif; ?>
                <?php if ($canDeleteBrand): ?>
                    <i class="fas fa-trash fa-lg text-danger btn-delete-brand" style="cursor: pointer;"
                        data-id="<?= $brand['brand_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaBrand"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$brandHtml = ob_get_clean();

echo $brandHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'brandpage') : '');
