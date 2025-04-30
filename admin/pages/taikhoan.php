<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$userId = $_SESSION['admin_id'] ?? null;

if (!$userId) {
    echo "<div class='p-4'>Bạn chưa đăng nhập.</div>";
    exit;
}

// Lấy thông tin người dùng
$user = $db->selectOne("SELECT * FROM users WHERE user_id = ?", [$userId]);
?>

<style>
    input[readonly],
    textarea[readonly] {
        background-color: #e9ecef !important;
        color: #495057;
        cursor: not-allowed;
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <h3 class="me-5">Thông tin tài khoản</h3>

        <button type="button" id="editBtn" class="btn btn-primary ms-5">Thay đổi</button>

    </div>

    <form id="userInfoForm" method="POST" action="" class="row g-3" style="max-width: 600px;">
        <div class="col-12">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username"
                value="<?= htmlspecialchars($user['username']) ?>" readonly>
        </div>
        <div class="col-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= htmlspecialchars($user['email']) ?>" readonly>
        </div>
        <div class="col-12">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone"
                value="<?= htmlspecialchars($user['phone']) ?>" readonly>
        </div>
        <div class="col-12">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea class="form-control" id="address" name="address" rows="2"
                readonly><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <div class="col-12 d-flex gap-2">
            
            <button type="submit" id="saveBtn" class="btn btn-success d-none">Lưu</button>
        </div>
    </form>
    <a href="./ajax/logout.php" class="btn btn-outline-danger mt-5">Đăng xuất</a>
</div>

<script>
    const fields = ['username', 'email', 'phone', 'address'];

    document.getElementById('editBtn').addEventListener('click', function () {
        fields.forEach(id => {
            document.getElementById(id).removeAttribute('readonly');
        });

        document.getElementById('editBtn').classList.add('d-none');
        document.getElementById('saveBtn').classList.remove('d-none');
    });

    document.getElementById('saveBtn').addEventListener('click', function () {
        const form = document.getElementById('userInfoForm');
        const formData = new FormData(form);

        fetch('ajax/luu_taikhoan.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    // Trả form về readonly
                    fields.forEach(id => {
                        document.getElementById(id).setAttribute('readonly', true);
                    });

                    // Đảo lại nút
                    document.getElementById('saveBtn').classList.add('d-none');
                    document.getElementById('editBtn').classList.remove('d-none');
                } else {
                    alert("❌ " + data.message);
                }
            })
            .catch(err => {
                alert("Lỗi kết nối khi lưu thông tin.");
                console.error(err);
            });
    });
</script>