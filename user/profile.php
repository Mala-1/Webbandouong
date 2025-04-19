<?php
session_start();

$upload_message = '';
$update_message = '';
$show_modal = false;

// Giả lập dữ liệu người dùng
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : [];

require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$user = $db->selectOne('SELECT * FROM users WHERE user_id = ?', [$user_id]);

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Cập nhật thông tin
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    // Cập nhật vào CSDL
    $updateSuccess = $db->execute(
        "UPDATE users SET email = ?, phone = ?, address = ? WHERE user_id = ?",
        [$email, $phone, $address, $user_id]
    );

    if ($updateSuccess) {
        echo "<script>alert('Cập nhật hồ sơ thành công!');</script>";
    } else {
        echo "<script>alert('Cập nhật hồ sơ thất bại!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light m-0">
    <div class="d-flex flex-column min-vh-100">
        <?php include '../includes/header.php'; ?>

        <div class="container py-5 flex-fill">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h4 class="mb-0">Hồ Sơ Người Dùng</h4>
                        </div>
                        <div class="card-body">
                            <?php if ($upload_message) echo $upload_message; ?>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong><i class="fas fa-user me-2"></i>Username:</strong> <?= $user['username'] ?>
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-envelope me-2"></i>Email:</strong> <?= $user['email'] ?>
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-phone me-2"></i>Số điện thoại:</strong> <?= $user['phone'] ?>
                                </li>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ:</strong> <?= $user['address'] ?>
                                </li>
                            </ul>

                            <div class="text-end mt-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="fas fa-edit me-1"></i> Chỉnh sửa hồ sơ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Chỉnh sửa hồ sơ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php if ($update_message) echo $update_message; ?>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($user['address']) ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" name="update_profile" class="btn btn-warning">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($show_modal): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('editProfileModal')).show();
            });
            
        </script>
    <?php endif; ?>
</body>

</html>