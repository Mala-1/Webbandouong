<?php
session_start();

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$userId = $_SESSION['admin_id'] ?? null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

// Lấy role_id của user
$sqlRole = "SELECT role_id FROM users WHERE user_id = ?";
$userData = $db->select($sqlRole, [$userId]);
$roleId = $userData[0]['role_id'] ?? null;

if (!$roleId) {
    echo "<p>Bạn không có quyền truy cập trang admin.</p>";
    exit;
}

// Lấy đầy đủ quyền theo module_name + action
$sqlPermDetail = "SELECT p.name AS module_name, rpd.action
                  FROM role_permission_details rpd
                  JOIN permissions p ON p.permission_id = rpd.permission_id
                  WHERE rpd.role_id = ?";
$permissionsRaw = $db->select($sqlPermDetail, [$roleId]);

$permissions = [];
foreach ($permissionsRaw as $perm) {
    $permissions[$perm['module_name']][] = $perm['action'];
}

// Gán vào session
$_SESSION['permissions'] = $permissions;

// Lấy các permission_id (nhóm quyền) mà role này có ít nhất 1 action
$sqlPermissions = "SELECT DISTINCT permission_id FROM role_permission_details WHERE role_id = ?";
$permissionRows = $db->select($sqlPermissions, [$roleId]);
$availableModules = array_column($permissionRows, 'permission_id');

// Ánh xạ permission_id sang thông tin menu
$menuItems = [
    1 => ['label' => 'Sản phẩm', 'icon' => 'fa-cubes', 'page' => 'sanpham'],
    2 => ['label' => 'Đơn hàng', 'icon' => 'fa-file-invoice', 'page' => 'donhang'],
    10 => ['label' => 'Khách Hàng', 'icon' => 'fa-users', 'page' => 'khachhang'],
    11 => ['label' => 'Nhân Viên', 'icon' => 'fa-user-tie', 'page' => 'nhanvien'],
    9 => ['label' => 'Nhà cung cấp', 'icon' => 'fa-truck', 'page' => 'NCC'],
    6 => ['label' => 'Phân quyền', 'icon' => 'fa-shield-alt', 'page' => 'phanquyen'],
    7 => ['label' => 'Thể loại', 'icon' => 'fa-layer-group', 'page' => 'theloai'],
    8 => ['label' => 'Thương hiệu', 'icon' => 'fa-copyright', 'page' => 'thuonghieu'],
    4 => ['label' => 'Phiếu nhập', 'icon' => 'fa-copyright', 'page' => 'phieunhap'],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="py-4 border d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <div class="d-flex flex-column ms-4 border-end me-4 border-2" style="width: 250px;">
            <h3 class="mb-4 ms-3">Admin Panel</h3>
            <ul class="nav flex-column gap-2">
                <li class="nav-item fs-5">
                    <a class="nav-link" href="?page=dashboard">
                        <i class="fas fa-table-columns me-1"></i> Dashboard
                    </a>
                </li>

                <?php foreach ($menuItems as $permId => $item): ?>
                <?php if (in_array($permId, $availableModules)): ?>
                <li class="nav-item fs-5">
                    <a class="nav-link" href="?page=<?= $item['page'] ?>">
                        <i class="fas <?= $item['icon'] ?> me-1"></i>
                        <?= $item['label'] ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Main content -->
        <div class="flex-fill">
            <?php
            $page = $_GET['page'] ?? 'dashboard';
            $allowedPages = ['dashboard', 'sanpham', 'donhang', 'khachhang', 'nhanvien', 'NCC', 'phanquyen', 'thuonghieu', 'theloai', 'phieunhap'];
            if (in_array($page, $allowedPages)) {
                include "pages/{$page}.php";
            } else {
                echo "<div class='p-4'>Trang không tồn tại!</div>";
            }
            ?>
        </div>
    </div>

    <script src="/assets/javascript/pagination.js"></script>
</body>

</html>