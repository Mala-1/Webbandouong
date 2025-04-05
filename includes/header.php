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
    <!-- Font Awesome (icon tìm kiếm) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">


    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f4f4;
    }

    .navbar {
        background-color: #212121;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand {
        font-family: 'Pacifico', cursive;
        color: #ffc107 !important;
        font-size: 1.8rem;
        letter-spacing: 1px;
    }

    .nav-link {
        color: #f8f9fa !important;
        font-weight: 500;
    }

    .nav-link:hover {
        color: #ffc107 !important;
    }

    .user-menu {
        color: #fff;
        font-size: 0.95rem;
    }

    .dropdown-menu {
        background-color: #343a40;
    }

    .dropdown-item {
        color: #fff;
    }

    .dropdown-item:hover {
        background-color: #495057;
    }

    .logo-img {
        width: 40px;
        margin-right: 10px;
    }

    form[role="search"] input:focus {
        box-shadow: 0 0 0 0.15rem rgba(255, 193, 7, 0.4);
        outline: none;
    }
    </style>
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4 py-2">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="assets/images/strarbucks.jpg" alt="logo" class="logo-img"> Đồ Uống Pro
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Đơn hàng</a></li>
            </ul>

            <!-- 🔍 Thanh tìm kiếm hiện đại -->
            <form class="d-flex me-3" action="search.php" method="GET" role="search">
                <input class="form-control form-control-sm me-2" type="search" name="query" placeholder="Tìm đồ uống..."
                    aria-label="Tìm kiếm" style="border-radius: 20px;">
                <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" style="color: #ffc107;">
                    <i class="fas fa-search fa-lg"></i>
                </button>
            </form>


            <!-- Dropdown người dùng -->
            <div class="dropdown">
                <a href="#" class="btn btn-dark dropdown-toggle" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    👤 <?= htmlspecialchars($username) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Hồ sơ</a></li>
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