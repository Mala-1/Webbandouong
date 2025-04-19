<?php
require_once '../includes/DBConnect.php';
require_once '../includes/pagination.php';

$db = DBConnect::getInstance();

// Phân trang
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Thể loại
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

$product_name = trim($_GET['product_name'] ?? '');
$categoryFilter = $_GET['category'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';


$whereClauses = [];
$params = [];

$effectiveCategory = !empty($categoryFilter) ? $categoryFilter : $categoryId;
if (!empty($effectiveCategory)) {
    $whereClauses[] = 'p.category_id = ?';
    $params[] = $effectiveCategory;
}

if (!empty($product_name)) {
    $whereClauses[] = '(p.name LIKE ?)';
    $params[] = "%$product_name%";
}

if (is_numeric($minPrice)) {
    $whereClauses[] = '(CASE WHEN po.price > 0 THEN po.price ELSE p.price END) >= ?';
    $params[] = $minPrice;
}

if (is_numeric($maxPrice)) {
    $whereClauses[] = '(CASE WHEN po.price > 0 THEN po.price ELSE p.price END) <= ?';
    $params[] = $maxPrice;
}

// Lọc
$sort = $_GET['sort'] ?? '';
$brands = json_decode($_GET['brands'] ?? '[]');
$packaging = json_decode($_GET['packaging'] ?? '[]');
$sizes = json_decode($_GET['sizes'] ?? '[]');

if ($sort == 'desc') {
    $sapXep = 'ORDER BY price DESC';
} else if ($sort == 'asc') {
    $sapXep = 'ORDER BY price ASC';
} else {
    $sapXep = 'ORDER BY p.product_id, po.packaging_option_id';
}
// Lọc theo thương hiệu
if (!empty($brands)) {
    $placeholders = implode(',', array_fill(0, count($brands), '?'));
    $whereClauses[] = 'p.brand_id IN (' . $placeholders . ')';
    $params = array_merge($params, $brands);
}
// Lọc theo loại đóng gói
if (!empty($packaging)) {
    $placeholders = implode(',', array_fill(0, count($packaging), '?'));
    $whereClauses[] = 'po.packaging_type IN (' . $placeholders . ')';
    $params = array_merge($params, $packaging);
}
// Lọc theo thể tích (size)
if (!empty($sizes)) {
    $volumeConditions = [];

    foreach ($sizes as $moc) {
        switch ($moc) {
            case '1':
                $volumeConditions[] = "(CASE 
                    WHEN p.size LIKE '%ml%' THEN CAST(REPLACE(p.size, 'ml', '') AS UNSIGNED)
                    WHEN p.size LIKE '%lít%' THEN CAST(REPLACE(REPLACE(p.size, 'lít', ''), ',', '.') AS DECIMAL(5,2)) * 1000
                    ELSE 0 END) < 500";
                break;

            case '2':
                $volumeConditions[] = "(CASE 
                    WHEN p.size LIKE '%ml%' THEN CAST(REPLACE(p.size, 'ml', '') AS UNSIGNED)
                    WHEN p.size LIKE '%lít%' THEN CAST(REPLACE(REPLACE(p.size, 'lít', ''), ',', '.') AS DECIMAL(5,2)) * 1000
                    ELSE 0 END) BETWEEN 500 AND 1000";
                break;

            case '3':
                $volumeConditions[] = "(CASE 
                    WHEN p.size LIKE '%ml%' THEN CAST(REPLACE(p.size, 'ml', '') AS UNSIGNED)
                    WHEN p.size LIKE '%lít%' THEN CAST(REPLACE(REPLACE(p.size, 'lít', ''), ',', '.') AS DECIMAL(5,2)) * 1000
                    ELSE 0 END) > 1000";
                break;
        }
    }

    if (!empty($volumeConditions)) {
        $whereClauses[] = '(' . implode(' OR ', $volumeConditions) . ')';
    }
}

// Gộp điều kiện WHERE
$whereSql = implode(' AND ', $whereClauses);



$products = $db->select("SELECT SQL_CALC_FOUND_ROWS
                            p.product_id,
                            p.name,
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
                        $sapXep
                        LIMIT $limit OFFSET $offset", $params);


$totalProductQuery = "SELECT FOUND_ROWS()";
$totalProductResult = $db->select($totalProductQuery);
$totalProduct = $totalProductResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalProduct / $limit);

// Khởi tạo phân tragn
$pagination = new Pagination($totalProduct, $limit, $page);
$baseQueryParams = [
    'page' => $page
];


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
foreach ($products as $product): ?>
    <div class="p-1 ps-md-3 mb-3">
        <div class="product-item border border-2 pt-2 shadow">
            <img alt="<?= $product['name'] ?>" class="img-fluid object-fit-contain mx-auto product-clickable"
                src=<?= '../assets/images/SanPham/' . $product['image'] ?>
                data-packaging-option-id="<?= $product['packaging_option_id'] ?>"
                data-product-id="<?= $product['product_id'] ?>" loading="lazy">
            <div class="mt-3">
                <p class="text-capitalize ellipsis-2-lines text-secondary mb-2 mx-2 product-clickable" data-packaging-option-id="<?= $product['packaging_option_id'] ?>">
                    <?= formatProductName($product['packaging_type'], $product['unit_quantity'], $product['name']) ?>
                </p>
                <p class="fw-medium fs-5 ms-2"><?= number_format($product['price']) . 'đ' ?></p>
                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                    href="product_detail.php?product_id=<?= $product['product_id'] ?>&packaging_option_id=<?= $product['packaging_option_id'] ?>">MUA</a>
            </div>
        </div>
    </div>
<?php endforeach;

$productsHtml = ob_get_clean();

echo $productsHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'pageproduct') : '');
?>