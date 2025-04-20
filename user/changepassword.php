<?php
session_start();

require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

// Khởi tạo biến
$update_message = '';
$alert_class = '';

// Kiểm tra đăng nhập
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit;
}

// Xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updatePasswordSuccess = $db->execute(
            "UPDATE users SET password = ? WHERE user_id = ?",
            [$hashed_password, $user_id]
        );

        if ($updatePasswordSuccess) {
            $update_message = '✅ Đổi mật khẩu thành công!';
            $alert_class = 'alert-success';
        } else {
            $update_message = '❌ Đổi mật khẩu thất bại. Vui lòng thử lại.';
            $alert_class = 'alert-danger';
        }
    } else {
        $update_message = '❌ Mật khẩu mới và xác nhận mật khẩu không khớp.';
        $alert_class = 'alert-danger';
    }
}

// Luôn lấy dữ liệu mới sau khi cập nhật
$user = $db->selectOne('SELECT * FROM users WHERE user_id = ?', [$user_id]);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .profile-container {
        display: flex;
        justify-content: space-between;
    }

    .profile-info {
        flex: 1;
        margin-right: 20px;
    }

    .change-password {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .change-password form {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    </style>
</head>

<body class="bg-light m-0">
    <div class="d-flex flex-column min-vh-100">
        <?php include '../includes/header.php'; ?>

        <div class="container py-5 flex-fill">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h4 class="mb-0">Thông Tin Tài Khoản</h4>
                        </div>
                        <div class="card-body profile-container">
                            <!-- Thông tin tài khoản -->
                            <div class="profile-info">
                                <h5>Hồ sơ người dùng</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong><i
                                                class="fas fa-user me-2"></i>Username:</strong>
                                        <?= htmlspecialchars($user['username']) ?></li>
                                    <li class="list-group-item"><strong><i
                                                class="fas fa-envelope me-2"></i>Email:</strong>
                                        <?= htmlspecialchars($user['email']) ?></li>
                                    <li class="list-group-item"><strong><i class="fas fa-phone me-2"></i>Số điện
                                            thoại:</strong>
                                        <?= htmlspecialchars($user['phone']) ?></li>
                                    <li class="list-group-item"><strong><i class="fas fa-map-marker-alt me-2"></i>Địa
                                            chỉ:</strong>
                                        <?= htmlspecialchars($user['address']) ?></li>
                                </ul>
                            </div>

                            <!-- Đổi mật khẩu -->
                            <div class="change-password">
                                <h5>Đổi mật khẩu</h5>
                                <?php if (!empty($update_message)): ?>
                                <div class="alert <?= $alert_class ?> fade show" id="autoAlert" role="alert">
                                    <?= $update_message ?>
                                </div>
                                <?php endif; ?>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Mật khẩu mới</label>
                                        <input type="password" class="form-control" id="new_password"
                                            name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="change_password" class="btn btn-danger">Cập nhật mật
                                            khẩu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tự động tắt alert
        const alertEl = document.getElementById('autoAlert');
        if (alertEl) {
            setTimeout(() => {
                alertEl.classList.remove('show');
                alertEl.classList.add('fade');
                setTimeout(() => alertEl.remove(), 1000);
            }, 1500);
        }
    });
    </script>
</body>

</html>