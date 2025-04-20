<?php
require_once '../../includes/DBConnect.php';
header('Content-Type: application/json');

$db = DBConnect::getInstance();

$start = $_GET['from'] ?? date('Y-m-01');
$end = $_GET['to'] ?? date('Y-m-d');
$limitProduct = isset($_GET['limit_product']) && is_numeric($_GET['limit_product']) ? (int)$_GET['limit_product'] : 5;
$limitCategory = isset($_GET['limit_category']) && is_numeric($_GET['limit_category']) ? (int)$_GET['limit_category'] : 5;

// 1. Tổng doanh thu
$sqlRevenue = "SELECT SUM(total_price) AS total FROM orders WHERE created_at BETWEEN ? AND ?";
$revenueRow = $db->select($sqlRevenue, [$start, $end]);
$totalRevenue = $revenueRow[0]['total'] ?? 0;

// 2. Sản phẩm bán chạy
$sqlTopProducts = "
    SELECT 
        p.product_id,
        p.name,
        SUM(od.quantity * CAST(po.unit_quantity AS UNSIGNED)) AS total_quantity
    FROM order_details od
    JOIN orders o ON o.order_id = od.order_id
    JOIN packaging_options po ON od.packaging_option_id = po.packaging_option_id
    JOIN products p ON od.product_id = p.product_id
    WHERE o.created_at BETWEEN ? AND ?
    GROUP BY p.product_id, p.name
    ORDER BY total_quantity DESC
    LIMIT $limitProduct
";
$topProducts = $db->select($sqlTopProducts, [$start, $end]);

// 3. Thể loại bán chạy
$sqlTopCategories = "
    SELECT 
        c.category_id,
        c.name,
        SUM(od.quantity * CAST(po.unit_quantity AS UNSIGNED)) AS total_quantity
    FROM order_details od
    JOIN orders o ON o.order_id = od.order_id
    JOIN packaging_options po ON od.packaging_option_id = po.packaging_option_id
    JOIN products p ON od.product_id = p.product_id
    JOIN categories c ON p.category_id = c.category_id
    WHERE o.created_at BETWEEN ? AND ?
    GROUP BY c.category_id, c.name
    ORDER BY total_quantity DESC
    LIMIT $limitCategory
";
$topCategories = $db->select($sqlTopCategories, [$start, $end]);

echo json_encode([
    'total_revenue' => $totalRevenue,
    'products' => $topProducts,
    'categories' => $topCategories
]);
exit;
