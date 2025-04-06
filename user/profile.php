<?php
session_start();

$upload_message = '';
$update_message = '';
$show_modal = false;

// Giả lập dữ liệu người dùng
$user = isset($_SESSION['user']) ? $_SESSION['user'] : [
    'name' => 'Nguyễn Văn A',
    'email' => 'nguyenvana@example.com',
    'phone' => '0123 456 789',
    'address' => '123 Đường ABC, Quận XYZ, TP HCM',
    'joined' => '01/01/2023',
    'avatar' => 'https://via.placeholder.com/120'
];

// Xử lý upload avatar
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowed_types) && $_FILES["avatar"]["size"] <= 5000000) {
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $user['avatar'] = $target_file;
            $_SESSION['user'] = $user;
        } else {
            $upload_message = '<div class="alert alert-danger" id="uploadMessage">Lỗi khi upload avatar.</div>';
        }
    } else {
        $upload_message = '<div class="alert alert-danger" id="uploadMessage">File không hợp lệ (chỉ chấp nhận JPG, PNG, GIF, dưới 5MB).</div>';
    }
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Cập nhật thông tin
    $user['name'] = htmlspecialchars($_POST['name']);
    $user['email'] = htmlspecialchars($_POST['email']);
    $user['phone'] = htmlspecialchars($_POST['phone']);
    $user['address'] = htmlspecialchars($_POST['address']);
    $_SESSION['user'] = $user;

    // Gán thông báo và hiển thị lại modal
    $update_message = '<div class="alert alert-success" id="updateMessage">Cập nhật thông tin thành công!</div>';
    $show_modal = true;
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Hồ Sơ</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="profile-card">
            <!-- Header -->
            <div class="profile-header">
                <h2>Hồ Sơ Người Dùng</h2>
            </div>

            <!-- Body -->
            <div class="profile-body">
                <!-- Container cho thông báo upload -->
                <div class="message-container">
                    <?php if ($upload_message) echo $upload_message; ?>
                </div>

                <!-- Avatar -->
                <div class="text-center">
                    <img src="<?php echo $user['avatar']; ?>" alt="Avatar" class="profile-avatar">
                </div>

                <!-- Thông tin -->
                <div class="mt-4">
                    <div class="info-item">
                        <strong><i class="fas fa-user me-2"></i> Họ tên:</strong>
                        <span><?php echo $user['name']; ?></span>
                    </div>
                    <div class="info-item">
                        <strong><i class="fas fa-envelope me-2"></i> Email:</strong>
                        <span><?php echo $user['email']; ?></span>
                    </div>
                    <div class="info-item">
                        <strong><i class="fas fa-phone me-2"></i> Số điện thoại:</strong>
                        <span><?php echo $user['phone']; ?></span>
                    </div>
                    <div class="info-item">
                        <strong><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ:</strong>
                        <span><?php echo $user['address']; ?></span>
                    </div>
                    <div class="info-item">
                        <strong><i class="fas fa-calendar-alt me-2"></i> Ngày tham gia:</strong>
                        <span><?php echo $user['joined']; ?></span>
                    </div>
                </div>

                <!-- Nút chỉnh sửa -->
                <div class="text-end mt-4">
                    <button type="button" class="btn edit-btn text-white" data-bs-toggle="modal"
                        data-bs-target="#editProfileModal">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa hồ sơ
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal chỉnh sửa -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Chỉnh sửa hồ sơ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" id="profileForm">
                        <div class="modal-body">
                            <!-- Container cho thông báo cập nhật -->
                            <div class="message-container">
                                <?php if ($update_message) echo $update_message; ?>
                            </div>

                            <!-- Upload avatar -->
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            </div>

                            <!-- Thông tin -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?php echo htmlspecialchars($user['address']); ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" name="update_profile" class="btn edit-btn text-white">Lưu thay
                                đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Script Bootstrap -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript để ẩn thông báo -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hideMessage = (id) => {
            const message = document.getElementById(id);
            if (message) {
                setTimeout(() => {
                    message.style.opacity = '0'; // Mờ dần
                    setTimeout(() => {
                        message.classList.add('hidden'); // Thêm class hidden sau khi mờ
                    }, 500); // Thời gian khớp với transition
                }, 3000); // Thời gian hiển thị thông báo
            }
        };

        hideMessage('uploadMessage');
        hideMessage('updateMessage');
    });
    </script>

    <!-- Hiển thị modal nếu có cập nhật -->
    <?php if ($show_modal): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        myModal.show();
    });
    </script>
    <?php endif; ?>
</body>

</html>