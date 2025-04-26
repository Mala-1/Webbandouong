<?php
// load modal bên ngoài
session_start();
$permissions = $_SESSION['permissions'] ?? [];
$canWrite = in_array('write', $permissions['Quản lý đơn nhập'] ?? []);
$canDelete = in_array('delete', $permissions['Quản lý đơn nhập'] ?? []);

require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';
$db = DBConnect::getInstance();

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_name = $_GET['search_name'] ?? '';
$search_email = $_GET['search_email'] ?? '';
$search_address = $_GET['search_address'] ?? '';

$whereClauses = [];
$params = [];

// ✅ Luôn kiểm tra nhà cung cấp chưa bị xoá
$whereClauses[] = 'is_deleted = 0';

if ($search_name !== '') {
    $whereClauses[] = 'name LIKE ?';
    $params[] = '%' . $search_name . '%';
}

if ($search_email !== '') {
    $whereClauses[] = 'email LIKE ?';
    $params[] = '%' . $search_email . '%';
}

if ($search_address !== '') {
    $whereClauses[] = 'address LIKE ?';
    $params[] = '%' . $search_address . '%';
}

// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM supplier $whereSql LIMIT $limit OFFSET $offset";
$suppliers = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalSupplier = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalSupplier / $limit);

// Khởi tạo phân tragn
$pagination = new Pagination($totalSupplier, $limit, $page);
$baseQueryParams = [
    'page' => $page
];

ob_start();
foreach ($suppliers as $supplier): ?>
    <tr>
        <td><?= $supplier['supplier_id'] ?></td>
        <td><?= $supplier['name'] ?></td>
        <td><?= $supplier['email'] ?></td>
        <td><?= $supplier['address'] ?></td>
        <?php if ($canWrite || $canDelete): ?>
            <td class="action-icons">
                
                <?php if ($canWrite): ?>
                    <i class="fas fa-pen text-primary btn-edit-supplier"
                        data-id="<?= $supplier['supplier_id'] ?>"
                        data-name="<?= htmlspecialchars($supplier['name']) ?>"
                        data-email="<?= htmlspecialchars($supplier['email']) ?>"
                        data-address="<?= htmlspecialchars($supplier['address']) ?>"></i>
                <?php endif; ?>

                <?php if ($canDelete): ?>
                    <i class="fas fa-trash text-danger btn-delete-supplier"
                        data-id="<?= $supplier['supplier_id'] ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#modalXoaNCC"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$supplierHtml = ob_get_clean();

echo $supplierHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'supplierpage') : '');
