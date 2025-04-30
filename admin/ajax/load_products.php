<?php
session_start();
$permissions = $_SESSION['permissions'] ?? [];
$productPermissions = $permissions['Quản lý sản phẩm'] ?? [];
$canWrite = in_array('write', $productPermissions);
$canDelete = in_array('delete', $productPermissions);

require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';
$db = DBConnect::getInstance();

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$whereClauses = [];
$params = [];

// Luôn lọc sản phẩm chưa bị xoá
$whereClauses[] = 'p.is_deleted = 0';

// Lọc theo category
if (!empty($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $whereClauses[] = 'p.category_id = ?';
    $params[] = (int)$_GET['category_id'];
}

if (isset($_GET['product_name']) && trim($_GET['product_name']) !== '') {
    $whereClauses[] = 'LOWER(p.name) LIKE ?';
    $params[] = '%' . strtolower($_GET['product_name']) . '%';
}

// Lọc theo brand
if (!empty($_GET['brand_id']) && is_numeric($_GET['brand_id'])) {
    $whereClauses[] = 'p.brand_id = ?';
    $params[] = (int)$_GET['brand_id'];
}

// Lọc theo khoảng giá
if (isset($_GET['price_min'])) {
    $min = trim($_GET['price_min']);
    if ($min !== '' && is_numeric($min)) {
        $whereClauses[] = '
            CASE 
                WHEN po.price IS NULL OR po.price = 0 THEN p.price 
                ELSE po.price 
            END >= ?
        ';
        $params[] = (float)$min;
    }
}

if (isset($_GET['price_max'])) {
    $max = trim($_GET['price_max']);
    if ($max !== '' && is_numeric($max)) {
        $whereClauses[] = '
            CASE 
                WHEN po.price IS NULL OR po.price = 0 THEN p.price 
                ELSE po.price 
            END <= ?
        ';
        $params[] = (float)$max;
    }
}


// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';


$sql = "
SELECT SQL_CALC_FOUND_ROWS
    p.product_id,
    pi.image,
    p.category_id,
    p.name,
    p.size,
    p.price,
    p.brand_id,
    c.name AS category_name,

    b.name AS brand_name,
    po.stock AS stock,
    po.packaging_type,
    p.origin,
    p.description
FROM products p
LEFT JOIN categories c ON p.category_id = c.category_id
LEFT JOIN brand b ON p.brand_id = b.brand_id

-- Chỉ lấy 1 packaging_option có unit_quantity = 1 (ưu tiên id nhỏ nhất)
LEFT JOIN (
    SELECT po1.*
    FROM packaging_options po1
    JOIN (
        SELECT product_id, MIN(packaging_option_id) AS min_id
        FROM packaging_options
        WHERE CAST(unit_quantity AS UNSIGNED) = 1 AND is_deleted = 0
        GROUP BY product_id
    ) po2 ON po1.packaging_option_id = po2.min_id
) po ON p.product_id = po.product_id

-- Lấy hình ảnh đầu tiên
LEFT JOIN (
    SELECT product_id, MIN(image) AS image
    FROM product_images
    GROUP BY product_id
) pi ON p.product_id = pi.product_id

$whereSql
LIMIT $limit OFFSET $offset
";

$products = $db->select($sql, $params);

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


$product_images = $db->select('SELECT * FROM product_images');
$productImageMap = [];
foreach ($product_images as $img) {
    $productImageMap[$img['product_id']][] = $img['image'];
}


ob_start();
foreach ($products as $product):
    $packaging_options = $db->select('SELECT 
                po.*, 
                CASE 
                    WHEN po.price > 0 THEN po.price 
                    ELSE p.price 
                END AS price
            FROM packaging_options po
            JOIN products p ON po.product_id = p.product_id
            WHERE po.product_id = ?
', [$product['product_id']]);

    // Ảnh mặc định lấy từ product_images (nếu có)
    $defaultImage = $productImageMap[$product['product_id']][0] ?? null;

    // Gán ảnh fallback nếu thiếu
    foreach ($packaging_options as &$option) {
        if (empty($option['image'])) {
            $option['image'] = $defaultImage;
        }
    }
    unset($option); // phá tham chiếu

    $dataPackaging = htmlspecialchars(json_encode($packaging_options), ENT_QUOTES, 'UTF-8');


    $images = $productImageMap[$product['product_id']] ?? [];
    $dataImages = htmlspecialchars(json_encode($images), ENT_QUOTES, 'UTF-8'); ?>

    <tr>
        <td><?= $product['product_id'] ?></td>
        <td><img src="/assets/images/SanPham/<?= $product['image'] ?>" alt="" class="img-fluid"
                style="width: 75px; height: 75px; object-fit: contain;"></td>
        <td class="ellipsis text-capitalize" style="max-width: 200px;"><?= $product['name'] ?></td>
        <td class="fw-bold text-success"><?= number_format($product['price']) ?>đ</td>
        <td><?= $product['category_name'] ?></td>
        <td><?= $product['brand_name'] ?></td>
        <td><?= $product['stock'] ?></td>
        <td class="text-capitalize"><?= $product['packaging_type'] ?></td>
        <td class="text-capitalize"><?= $product['origin'] ?></td>
        <td class="ellipsis" style="max-width: 150px;"><?= $product['description'] ?></td>
        <?php if ($canWrite || $canDelete): ?>
            <td>
                <a href="#" class="text-info me-3 btn-xem-sanpham text-decoration-none"
                    data-id="<?= $product['product_id'] ?>"
                    data-name="<?= htmlspecialchars($product['name']) ?>"
                    data-size="<?= $product['size'] ?>"
                    data-price="<?= $product['price'] ?>"
                    data-category="<?= $product['category_id'] ?>"
                    data-brand="<?= $product['brand_id'] ?>"
                    data-origin="<?= $product['origin'] ?>"
                    data-unit="<?= $product['packaging_type'] ?>"
                    data-description="<?= htmlspecialchars($product['description']) ?>"
                    data-images="<?= $dataImages ?>"
                    data-packaging="<?= $dataPackaging ?>">
                    <i class="fas fa-eye fa-lg"></i>
                </a>
                <?php if (in_array('write', $productPermissions)): ?>
                    <a href="#" class="text-primary me-3 btn-sua-sanpham text-decoration-none"
                        data-id="<?= $product['product_id'] ?>"
                        data-name="<?= htmlspecialchars($product['name']) ?>"
                        data-size="<?= $product['size'] ?>"
                        data-price="<?= $product['price'] ?>"
                        data-category="<?= $product['category_id'] ?>"
                        data-brand="<?= $product['brand_id'] ?>"
                        data-origin="<?= $product['origin'] ?>"
                        data-unit="<?= $product['packaging_type'] ?>"
                        data-description="<?= htmlspecialchars($product['description']) ?>"
                        data-images="<?= $dataImages ?>"
                        data-packaging="<?= $dataPackaging ?>">
                        <i class="fas fa-pen fa-lg"></i>
                    </a>
                <?php endif; ?>

                <?php if (in_array('delete', $productPermissions)): ?>
                    <a href="#" class="text-danger btn-delete-product"
                        data-id="<?= $product['product_id'] ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#modalXoaSanPham">
                        <i class="fas fa-trash fa-lg"></i>
                    </a>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$productsHtml = ob_get_clean();

echo $productsHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'pageproduct') : '');
?>