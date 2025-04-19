<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="py-4 border d-flex" style="min-height: 100vh;">
        <!-- Menu -->
        <div class="d-flex flex-column ms-4 border-end me-4 border-2" style="width: 250px;">
            <h3 class="mb-4 ms-3">Admin Panel</h3>
    
            <ul class="nav flex-column gap-2">
                <li class="nav-item fs-5">
                    <a class="nav-link active" href="?page=dashboard">
                        <i class="fas fa-table-columns me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item fs-5">
                    <a class="nav-link active" href="?page=sanpham">
                        <i class="fas fa-cubes me-1"></i>
                        Sản phẩm
                    </a>
                </li>
                <li class="nav-item fs-5">
                    <a class="nav-link active" href="?page=donhang">
                        <i class="fas fa-file-invoice me-2"></i>
                        Đơn Hàng
                    </a>
                </li>
                <li class="nav-item fs-5">
                    <a class="nav-link active" href="?page=NCC">
                        <i class="fas fa-file-invoice me-2"></i>
                        Nhà cung cấp
                    </a>
                </li>
                <!-- Thêm các mục khác -->
            </ul>
        </div>

        <!-- content -->
        <div class="flex-fill">
            <?php
                $page = $_GET['page'] ?? 'dashboard';
                $allowedPages = ['dashboard', 'sanpham', 'donhang', 'NCC'];
                if (in_array($page, $allowedPages)) {
                    include "pages/{$page}.php";
                } else {
                    echo "<div class='p-4'>Trang không tồn tại!</div>";
                }
            ?>
        </div>
    </div>
    
    <script src="/assets/javascript/pagination.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
