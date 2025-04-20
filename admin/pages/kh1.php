<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

// Kiểm tra quyền truy cập
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$permissions = $_SESSION['permissions'] ?? [];
$canReadCustomers = in_array('view', $permissions['customers'] ?? []);
$canWriteCustomers = in_array('edit', $permissions['customers'] ?? []);
$canDeleteCustomers = in_array('edit', $permissions['customers'] ?? []); // Giả định xóa cũng cần quyền edit
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Khách Hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    body {
        background-color: #f1f1f1;
        margin: 0;
        padding: 0;
    }

    .custom-container {
        padding: 0 25px;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        font-size: 16px;
    }

    .custom-table th,
    .custom-table td {
        padding: 14px 18px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    .custom-table th {
        background-color: #f5f5f5;
        font-weight: 600;
    }

    .action-icons i {
        cursor: pointer;
        margin: 0 6px;
        font-size: 18px;
    }

    input[type="text"],
    input[type="email"] {
        font-size: 15px;
    }
    </style>
</head>

<body>
    <div class="custom-container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <?php if ($canWriteCustomers): ?>
            <button class="btn btn-primary btn-add-customer" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="fa fa-plus"></i> THÊM
            </button>
            <?php endif; ?>
            <input style="margin: auto;" name="username" type="text" class="form-control w-25"
                placeholder="🔍 Username">
        </div>
    </div>

    <div class="custom-container mt-4 bg-white rounded">
        <div style="padding: 10px;border-radius: 5px;">
            <div class="d-flex gap-2 mb-4">
                <input type="text" class="form-control" placeholder="Điện thoại" name="phone">
                <input type="text" class="form-control" placeholder="Địa chỉ" name="address">
                <input type="email" class="form-control" placeholder="Email" name="email">
            </div>
            <table class="custom-table text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Mật khẩu</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <?php if ($canWriteCustomers || $canDeleteCustomers): ?>
                        <th>Chức năng</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="customer-wrap">
                    <!-- Dữ liệu sẽ được load qua AJAX -->
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <!-- Phân trang sẽ được load qua AJAX -->
        </div>
    </div>

    <!-- Modal Thêm khách hàng -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="customerForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Thêm Khách Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="Điện thoại">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" placeholder="Địa chỉ">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận xóa khách hàng -->
    <div class="modal fade" id="modalXoaCustomer" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa khách hàng có ID <strong id="customer-id-display"></strong> không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="btnXacNhanXoa">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa khách hàng -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editCustomerForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Sửa Khách Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" name="user_id" id="editCustomerId">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="editUsername" class="form-control"
                                placeholder="Username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" id="editPassword" class="form-control"
                                placeholder="Mật khẩu (để trống nếu không đổi)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" name="phone" id="editPhone" class="form-control"
                                placeholder="Điện thoại">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="editAddress" class="form-control"
                                placeholder="Địa chỉ">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    let currentFilterParams = '';

    function loadCustomers(page = 1, params = "") {
        const customerWrap = document.querySelector('.customer-wrap');
        const paginationWrap = document.querySelector('.pagination-wrap');
        fetch('/admin/ajax/load_customer.php?page=' + page + params)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok: ' + res.status);
                }
                return res.text();
            })
            .then(data => {
                const parts = data.split('SPLIT');
                customerWrap.innerHTML = parts[0] || '';
                paginationWrap.innerHTML = parts[1] || '';
            })
            .catch(error => {
                console.error('Error loading customers:', error);
                customerWrap.innerHTML =
                    '<tr><td colspan="8">Lỗi khi tải dữ liệu khách hàng. Vui lòng thử lại.</td></tr>';
            });
    }
    loadCustomers(1);

    document.addEventListener("pagination:change", function(e) {
        const {
            page,
            target
        } = e.detail;
        if (target === "customerpage") {
            loadCustomers(page, currentFilterParams);
        }
    });

    // Lắng nghe sự kiện tìm kiếm
    document.querySelectorAll('input[name="username"], input[name="phone"], input[name="address"], input[name="email"]')
        .forEach(input => {
            input.addEventListener('input', function() {
                const username = document.querySelector('input[name="username"]').value.trim();
                const phone = document.querySelector('input[name="phone"]').value.trim();
                const address = document.querySelector('input[name="address"]').value.trim();
                const email = document.querySelector('input[name="email"]').value.trim();
                currentFilterParams =
                    `&search_username=${encodeURIComponent(username)}&search_phone=${encodeURIComponent(phone)}&search_address=${encodeURIComponent(address)}&search_email=${encodeURIComponent(email)}`;
                loadCustomers(1, currentFilterParams);
            });
        });

    let idDangXoa = null;

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-customer')) {
            e.preventDefault();
            const btn = e.target.closest('.btn-delete-customer');
            idDangXoa = btn.getAttribute('data-id');
            document.getElementById('customer-id-display').textContent = idDangXoa;
        }
    });

    document.getElementById('btnXacNhanXoa').addEventListener('click', function() {
        if (!idDangXoa) return;

        fetch('/admin/ajax/delete_customer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: idDangXoa
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadCustomers(1, currentFilterParams);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaCustomer'));
                    modal.hide();
                } else {
                    alert('Xóa thất bại: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting customer:', error);
                alert('Lỗi khi xóa khách hàng. Vui lòng thử lại.');
            });
    });

    document.getElementById("customerForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const username = this.username.value.trim();
        const password = this.password.value.trim();
        const phone = this.phone.value.trim();
        const address = this.address.value.trim();
        const email = this.email.value.trim();

        fetch('/admin/ajax/add_customer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username,
                    password,
                    phone,
                    address,
                    email
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                    modal.hide();
                    loadCustomers(1);
                    this.reset();
                    alert('Thêm khách hàng thành công!');
                } else {
                    alert(data.message || 'Thêm khách hàng thất bại');
                }
            })
            .catch(error => {
                console.error('Error adding customer:', error);
                alert('Lỗi khi thêm khách hàng. Vui lòng thử lại.');
            });
    });

    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit-customer');
        if (editBtn) {
            const userId = editBtn.dataset.id;
            const username = editBtn.dataset.username;
            const phone = editBtn.dataset.phone;
            const address = editBtn.dataset.address;
            const email = editBtn.dataset.email;
            document.getElementById('editCustomerId').value = userId;
            document.getElementById('editUsername').value = username;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editAddress').value = address;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPassword').value = '';
            const editModal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
            editModal.show();
        }
    });

    document.getElementById("editCustomerForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const user_id = this.user_id.value;
        const username = this.username.value.trim();
        const password = this.password.value.trim();
        const phone = this.phone.value.trim();
        const address = this.address.value.trim();
        const email = this.email.value.trim();

        fetch('/admin/ajax/update_customer.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id,
                    username,
                    password,
                    phone,
                    address,
                    email
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCustomerModal'));
                    modal.hide();
                    loadCustomers(1, currentFilterParams);
                    alert('Cập nhật thành công!');
                } else {
                    alert(data.message || 'Lỗi cập nhật');
                }
            })
            .catch(error => {
                console.error('Error updating customer:', error);
                alert('Lỗi khi cập nhật khách hàng. Vui lòng thử lại.');
            });
    });
    </script>
</body>

</html>