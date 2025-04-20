<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$db = DBConnect::getInstance();

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = max(($page - 1) * $limit, 0); // offset bắt đầu từ 0
$params = [];

$conditions = ["s.is_deleted = 0"]; // Chỉ lấy NCC chưa bị xoá mềm

if (!empty($search)) {
    $conditions[] = "s.name LIKE ?";
    $params[] = "%" . $search . "%";
}

$whereSql = implode(" AND ", $conditions);

$sql = "
    SELECT SQL_CALC_FOUND_ROWS
        s.supplier_id,
        s.name,
        s.email,
        s.address
    FROM supplier s
    WHERE $whereSql
    LIMIT $limit OFFSET $offset
";

$rows = $db->select($sql, $params);

// Tổng số bản ghi
$total = $db->select("SELECT FOUND_ROWS()")[0]['FOUND_ROWS()'];
$pagination = new Pagination($total, $limit, $page);

// Render HTML
ob_start();
foreach ($rows as $row): ?>
    <tr>
        <td><?= $row['supplier_id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td>
            <button class="btn btn-success btn-sm" onclick="selectSupplier(this)"
                data-supplier-id="<?= $row['supplier_id'] ?>"
                data-supplier-name="<?= htmlspecialchars($row['name']) ?>">
                Chọn
            </button>
        </td>
    </tr>
<?php endforeach;
$supplierHtml = ob_get_clean();

header('Content-Type: application/json');
echo json_encode([
    "supplier_html" => $supplierHtml,
    "pagination" => $total > $limit ? $pagination->render([], 'supplierpage') : ""
]);
