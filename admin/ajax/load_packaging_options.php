<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$db = DBConnect::getInstance();

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = max(($page - 1) * $limit, 1);
$params = [];

$conditions = ["1"];

if (!empty($search)) {
    $conditions[] = "p.product_name LIKE ?";
    $params[] = "%" . $search . "%";
}

// Gộp điều kiện thành chuỗi WHERE
$whereSql = implode(" AND ", $conditions); // KHÔNG thêm "WHERE" ở đây

// ✅ Truy vấn lấy sản phẩm + packaging_option có unit_quantity = 1
$sql = "
SELECT SQL_CALC_FOUND_ROWS
                            p.product_id,
                            p.name as product_name,
                            p.size,
                            po.packaging_option_id,
                            po.packaging_type,
                            po.unit_quantity,
                            COALESCE(
                                po.image,
                                (
                                    SELECT pi.image 
                                    FROM product_images pi 
                                    WHERE pi.product_id = p.product_id 
                                    ORDER BY pi.image ASC 
                                    LIMIT 1
                                )
                            ) AS image,
                            CASE 
                                WHEN po.price IS NULL OR po.price = 0 THEN p.price
                                ELSE po.price
                            END AS price
                        FROM products p
                        LEFT JOIN packaging_options po ON po.product_id = p.product_id
                        WHERE $whereSql
                        LIMIT $limit OFFSET $offset
";

$rows = $db->select($sql, $params);

// Lấy tổng bản ghi cho phân trang
$total = $db->select("SELECT FOUND_ROWS()")[0]['FOUND_ROWS()'];
$pagination = new Pagination($total, $limit, $page);

function formatProductName($packaging_type, $unit_quantity, $product_name)
{
    $packaging = trim($packaging_type . ' ' . $unit_quantity);
    // Loại trùng nếu packaging_type đã nằm trong unit_quantity
    if (stripos($unit_quantity, $packaging_type) !== false) {
        $packaging = $unit_quantity;
    }

    // Với unit_quantity là "1 lon", "1 chai", có thể tối giản
    if (preg_match('/^1\s+[[:alpha:]]+$/u', $unit_quantity)) {
        $packaging = $packaging_type; // chỉ in "Lon" hoặc "Chai"
    }

    return "{$packaging} {$product_name}";
}

ob_start();
foreach ($rows as $row): ?>
    <tr>
        <td><?= formatProductName($row['packaging_type'], $row['unit_quantity'], $row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['packaging_type']) ?></td>
        <td><?= htmlspecialchars($row['unit_quantity']) ?></td>
        <td><?= number_format($row['price']) ?>đ</td>
        <td><img src="../../assets/images/SanPham/<?= $row['image'] ?>" width="50" height="50" style="object-fit:cover;"></td>
        <td>
            <button class="btn btn-success btn-sm" onclick="selectPackaging(this)"
                data-product-id="<?= $row['product_id'] ?>"
                data-product="<?= htmlspecialchars($row['product_name']) ?>"
                data-packaging="<?= htmlspecialchars($row['packaging_type']) ?> - <?= $row['unit_quantity'] ?>"
                data-price="<?= $row['price'] ?>">
                Chọn
            </button>
        </td>
    </tr>
<?php endforeach;
$packagingHtml = ob_get_clean();

echo json_encode([
    "packaging_html" => $packagingHtml,
    "pagination" => $total > $limit ? $pagination->render([], 'packagingpage') : ""
]);
