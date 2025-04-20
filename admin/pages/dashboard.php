<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$start = date('Y-m-01');
$end = date('Y-m-d');

// 1. Doanh thu tháng này
$sqlRevenue = "SELECT SUM(total_price) AS total FROM orders WHERE created_at BETWEEN ? AND ?";
$row = $db->select($sqlRevenue, [$start, $end]);
$revenue = $row[0]['total'] ?? 0;

// 2. Top 3 sản phẩm bán chạy
$sqlTopProducts = "
    SELECT p.name, SUM(od.quantity * CAST(po.unit_quantity AS UNSIGNED)) AS total_quantity
    FROM order_details od
    JOIN orders o ON o.order_id = od.order_id
    JOIN packaging_options po ON od.packaging_option_id = po.packaging_option_id
    JOIN products p ON od.product_id = p.product_id
    WHERE o.created_at BETWEEN ? AND ?
    GROUP BY p.product_id
    ORDER BY total_quantity DESC
    LIMIT 3
";
$topProducts = $db->select($sqlTopProducts, [$start, $end]);

// 3. Đơn hàng chưa xử lý
$sqlPendingOrders = "SELECT COUNT(*) AS count FROM orders WHERE status = 'Chờ xử lý'";
$pendingCount = $db->select($sqlPendingOrders)[0]['count'] ?? 0;
?>

<!-- DASHBOARD UI -->
<div class="p-4 h-100" style="background-color: #f0f0f0;">
    <div>
        <h3 class="m-0">Dashboard - Quản trị</h3>
        <span class="fs-6">Quản lý thông tin bán đồ uống</span>
    </div>

    <div class="row align-items-stretch mt-3">
        <!-- Doanh thu -->
        <div class="col-4 p-2 d-flex">
            <div class="bg-white py-3 px-4 rounded w-100">
                <h5><i class="fas fa-chart-line me-2 text-danger"></i>Doanh thu tháng này</h5>
                <h3 class="text-danger fw-bold m-0 mt-3"><?= number_format($revenue) ?>đ</h3>
                <small>So với tháng trước</small>
            </div>
        </div>

        <!-- SP bán chạy -->
        <div class="col-4 p-2 d-flex">
            <div class="bg-white py-3 px-4 rounded w-100">
                <h5><i class="fas fa-fire me-2 text-success"></i>Sản phẩm bán chạy trong tháng</h5>
                <div class="mt-3">
                    <?php foreach ($topProducts as $prod): ?>
                        <div class="d-flex justify-content-between mt-1">
                            <span><?= htmlspecialchars($prod['name']) ?></span>
                            <span><?= number_format($prod['total_quantity']) ?> đơn vị</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Đơn chưa xử lý -->
        <div class="col-4 p-2 d-flex">
            <div class="bg-white py-3 px-4 rounded w-100">
                <h5><i class="fas fa-truck-loading me-2 text-warning"></i>Đơn hàng chưa xử lý</h5>
                <p class="text-warning fs-4 m-0 mt-3 fw-bold"><?= $pendingCount ?> đơn chưa xử lý</p>
                <small>Chờ xác nhận hoặc vận chuyển</small>
            </div>
        </div>
    </div>
</div>
