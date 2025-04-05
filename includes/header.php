<?php
session_start();
$username = $_SESSION['username'] ?? 'Khách';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đồ Uống Pro</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4 py-2">
        <a class="navbar-brand d-flex align-items-center" href="../index.php">
            <img src="../assets/images/strarbucks.jpg" alt="logo" class="logo-img"> Đồ Uống Pro
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item"><a class="nav-link" href="../index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Giỏ hàng</a></li>
            </ul>

            <!-- 🔍 Thanh tìm kiếm -->
            <form class="d-flex me-3" action="search.php" method="GET" role="search">
                <input class="form-control form-control-sm me-2" type="search" name="query" placeholder="Tìm đồ uống..."
                    aria-label="Tìm kiếm" style="border-radius: 20px;">

                <!-- Icon 🔍 -->
                <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" style="color: #ffc107;">
                    <i class="fas fa-search fa-lg"></i>
                </button>

                <!-- Icon 🧃 Filter -->
                <div class="dropdown ms-2">
                    <a href="#" class="btn btn-sm p-0 border-0 bg-transparent text-warning" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-filter fa-lg"></i>
                    </a>
                    <div class="dropdown-menu p-3 shadow"
                        style="min-width: 200px; max-width: 300px; position: absolute; z-index: 1050; left: 50%; transform: translateX(-50%); top: 40px;"
                        id="filterDropdown">
                        <div class="mb-2">
                            <label class="form-label mb-1" style="color: #ffc107;">Tên gần đúng</label>
                            <input type="text" name="query" class="form-control form-control-sm"
                                placeholder="Nhập tên...">
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1" style="color: #ffc107;">Thể loại</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">-- Tất cả --</option>
                                <option value="coffee">Cà phê</option>
                                <option value="tea">Trà</option>
                                <option value="juice">Nước ép</option>
                                <option value="smoothie">Sinh tố</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label mb-1" style="color: #ffc107;">Giá từ - đến</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min" class="form-control form-control-sm" placeholder="Min"
                                    min="0">
                                <input type="number" name="max" class="form-control form-control-sm" placeholder="Max"
                                    min="0">
                            </div>
                        </div>

                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-dark btn-sm">Lọc</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Dropdown người dùng -->
            <div class="dropdown">
                <a href="#" class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    👤 <?= htmlspecialchars($username) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="../user/profile.php"><i class=" fas fa-user"></i> Hồ sơ</a></li>
                    <li><a class="dropdown-item" href="changepassword.php"><i class="fas fa-key"></i> Đổi mật khẩu</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </div>

    </nav>

    <div class="container mt-4">