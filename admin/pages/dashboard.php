<?php
require_once '../includes/DBConnect.php';

$db = DBConnect::getInstance();

$db = DBConnect::getInstance();

// Lấy doanh thu tháng này
$doanhThuThangNay = $db->selectOne("
    SELECT SUM(total_price) AS total_revenue
    FROM orders
    WHERE created_at BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE();
", []);

// Lấy doanh thu tháng trước (cùng số ngày)
$soNgayTrongThang = date('j'); // Số ngày từ ngày 1 đến ngày hiện tại
$doanhThuThangTruoc = $db->selectOne("
    SELECT SUM(total_price) AS total_revenue
    FROM orders
    WHERE created_at BETWEEN 
        DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01')
        AND 
        DATE_ADD(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01'), INTERVAL $soNgayTrongThang - 1 DAY);
", []);

// Lấy danh sách sản phẩm bán chạy trong tháng
$sanPhamBanChay = $db->select("
    SELECT p.name, SUM(quantity) AS total_quantity
    FROM order_details o
    INNER JOIN products p ON o.product_id = p.product_id
    JOIN orders od ON od.order_id = o.order_id 
    WHERE od.created_at BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()
    GROUP BY p.name
    ORDER BY total_quantity DESC
    LIMIT 3;
", []);

// Lấy số đơn hàng chưa xử lý
$donHangChuaXuLy = $db->selectOne("
    SELECT COUNT(*) AS pending_orders
    FROM orders
    WHERE status = 'Chờ xử lý';
", []);

$permissions = $_SESSION['permissions'] ?? [];
$canRead = in_array('read', $permissions['Xem báo cáo'] ?? []);

$classDoanhThu = ($doanhThuThangNay['total_revenue'] - $doanhThuThangTruoc['total_revenue']) > 0 ? 'text-success' : 'text-danger';


?>

<style>
    .text-success {
        color: #42c175 !important;
    }

    .text-danger {
        color: #f84c49 !important;
    }
</style>
<div class="p-4 h-100" style="background-color: #f0f0f0;">
    <!-- Tiêu đề -->
    <div>
        <h3 class="m-0">Dashboard - Quản trị</h3>
        <span class="fs-6">Quản lý thông tin bán đồ uống</span>
    </div>

    <!-- content -->
    <div>
        <!-- Thống kê -->
        <div class="row align-items-stretch">
            <?php if ($canRead): ?>
                <div class="col-4 p-2 d-flex">
                    <div class="bg-white py-3 px-4 rounded w-100">
                        <h5>Doanh thu đến ngày <?= $soNgayTrongThang ?> tháng này</h5>
                        <h3 class="<?= $classDoanhThu ?> fw-bold m-0 mt-3">
                            <?= number_format($doanhThuThangNay['total_revenue'], 0) ?>đ
                        </h3>
                        <small>
                            <?= round((($doanhThuThangNay['total_revenue'] - $doanhThuThangTruoc['total_revenue']) / $doanhThuThangTruoc['total_revenue']) * 100, 2) ?>%
                            so với cùng kỳ tháng trước
                        </small>
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-4 p-2 d-flex">
                <div class="bg-white py-3 px-4 rounded w-100">
                    <h5>Sản phẩm bán chạy trong tháng</h5>
                    <div class="mt-3">
                        <?php foreach ($sanPhamBanChay as $sanPham): ?>
                            <div class="d-flex justify-content-between mt-1">
                                <span style="max-width: 70%;"><?= $sanPham['name'] ?></span>
                                <span><?= number_format($sanPham['total_quantity']) ?> sản phẩm</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-4 p-2 d-flex">
                <div class="bg-white py-3 px-4 rounded w-100">
                    <h5>Đơn hàng chưa xử lý</h5>
                    <p class="text-warning fs-4 m-0 mt-3 fw-bold">
                        <?= $donHangChuaXuLy['pending_orders'] ?> đơn chưa xử lý
                    </p>
                    <small>Chờ xác nhận hoặc vận chuyển</small>
                </div>
            </div>
        </div>
        <!-- vẽ biểu đồ -->
        <?php
        // Dữ liệu cho biểu đồ doanh thu
        $chartData = [
            'labels' => [
                'Tháng trước',
                'Tháng này'
            ],
            'datasets' => [
                [
                    'data' => [
                        $doanhThuThangTruoc['total_revenue'],
                        $doanhThuThangNay['total_revenue']
                    ],
                    'backgroundColor' => [
                        '#f84c49',
                        '#42c175'
                    ]
                ]
            ]
        ];
        ?>
        <?php if ($canRead): ?>
            <div class="row">
                <div class="col-4 p-2">
                    <div class="bg-white rounded p-2">
                        <h5>Biểu đồ Doanh thu</h5>
                        <canvas id="doanhThuChart" width="400" height="200"></canvas>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Lấy dữ liệu từ PHP
            const chartData = <?= json_encode($chartData) ?>;

            const ctx = document.getElementById('doanhThuChart').getContext('2d');
            const doanhThuChart = new Chart(ctx, {
                type: 'bar', // Loại biểu đồ (bar, line, pie, etc.)
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Ẩn chú thích nếu không cần thiết
                        }
                    },
                    responsive: true
                }
            });
        </script>
    </div>

</div>