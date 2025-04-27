<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

// Phân quyền (nếu có)
$permissions = $_SESSION['permissions'] ?? [];
$canReadCustomer = in_array('read', $permissions['Quản lý khách hàng'] ?? []);
$canWriteCustomer = in_array('write', $permissions['Quản lý khách hàng'] ?? []);
$canDeleteCustomer = in_array('delete', $permissions['Quản lý khách hàng'] ?? []);
?>

<div class="p-3 d-flex align-items-center rounded" style="background-color: #f0f0f0; height: 80px;">
    <?php if ($canWriteCustomer): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="fa-solid fa-plus me-1"></i> THÊM
        </button>
    <?php endif; ?>

    <div class="flex-grow-1">
        <form class="d-flex justify-content-center mx-auto" style="max-width: 400px; width: 100%;" role="search">
            <input class="customer-search form-control me-2" type="search" placeholder="Tìm kiếm tên khách hàng" aria-label="Search" name="search-username">
            <button type="button" class="btn-search btn btn-sm p-0 border-0 bg-transparent">
                <i class="fas fa-search fa-lg"></i>
            </button>
        </form>
    </div>
</div>

<!-- Bảng danh sách khách hàng -->
<div class="table-responsive mt-4 pe-3">
    <table class="table align-middle table-bordered">
        <thead class="table-light text-center">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <?php if ($canWriteCustomer || $canDeleteCustomer): ?>
                    <th>Chức năng</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="customer-wrap text-center align-middle">
            <!-- Dữ liệu khách hàng sẽ được load từ Ajax -->
        </tbody>
    </table>
</div>
<div class="pagination-wrap"></div>

<!-- Modal thêm khách hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
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
                        <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" placeholder="Số điện thoại">
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

<!-- Modal sửa khách hàng -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
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
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" id="editPhone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" id="editAddress" class="form-control">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="editNewPassword" class="form-control" placeholder="Nhập mật khẩu mới (nếu đổi)" style="display: none;">
                            <button type="button" id="generatePasswordBtn" class="btn btn-outline-primary">Cấp mật khẩu mới</button>
                        </div>
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

<!-- Modal xác nhận xóa khách hàng -->
<div class="modal fade" id="modalXoaCustomer" tabindex="-1" aria-labelledby="deleteCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xoá khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xoá khách hàng có mã <strong id="customer-id-display"></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaCustomer">Xoá</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFilterParams = '';

    // Hàm load danh sách khách hàng
    function loadCustomers(page = 1, params = "") {
        const customerWrap = document.querySelector('.customer-wrap');
        const paginationWrap = document.querySelector('.pagination-wrap');

        fetch('ajax/load_khachhang.php?page=' + page + params)
            .then(res => res.text())
            .then(data => {
                const parts = data.split('SPLIT');
                customerWrap.innerHTML = parts[0] || '';
                paginationWrap.innerHTML = parts[1] || '';
            });
    }

    // Gọi lần đầu khi trang load
    loadCustomers(1);

    // Bắt sự kiện phân trang
    document.addEventListener("pagination:change", function(e) {
        const {
            page,
            target
        } = e.detail;

        if (target === "khachhangpage") {
            loadCustomers(page, currentFilterParams);
        }
    });

    // Bắt sự kiện tìm kiếm username
    document.querySelector('input[name="search-username"]').addEventListener('input', function() {
        const searchValue = this.value.trim();
        currentFilterParams = searchValue ? '&search_name=' + encodeURIComponent(searchValue) : '';
        loadCustomers(1, currentFilterParams);
    });

    // ✅ Thêm khách hàng - kiểm tra đầy đủ thông tin trước khi gửi
    const addCustomerForm = document.getElementById("customerForm");

    if (addCustomerForm) {
        addCustomerForm.addEventListener("submit", function(e) {
            e.preventDefault();

            // Lấy dữ liệu từ form
            const username = this.querySelector('input[name="username"]').value.trim();
            const password = this.querySelector('input[name="password"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();

            // Kiểm tra các trường bắt buộc
            if (!username || !password || !email) {
                alert('Vui lòng điền đầy đủ thông tin bắt buộc (Tên đăng nhập, Mật khẩu, Email, Trạng thái)');
                return;
            }

            const formData = new FormData(this);

            fetch('ajax/add_khachhang.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addCustomerModal'));
                        modal.hide();
                        loadCustomers(1); // reload danh sách
                        this.reset();
                        alert('Thêm khách hàng thành công!');
                    } else {
                        alert(data.message || 'Thêm khách hàng thất bại');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi thêm khách hàng');
                });
        });
    }

    // ✅ Xoá khách hàng
    let idDangXoa = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-customer');
        if (btn) {
            e.preventDefault();
            idDangXoa = btn.getAttribute('data-id');
            document.getElementById('customer-id-display').textContent = idDangXoa;
        }
    });

    document.getElementById('btnXacNhanXoaCustomer').addEventListener('click', function() {
        if (!idDangXoa) return;

        fetch('ajax/delete_user.php', {
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
                    alert('Xóa khách hàng thành công!');
                } else {
                    alert('Xóa thất bại: ' + data.message);
                }
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaCustomer'));
                modal.hide();
            });
    });

    // ✅ Sửa khách hàng
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit-customer');
        if (editBtn) {
            document.getElementById('editCustomerId').value = editBtn.dataset.id || '';
            document.getElementById('editUsername').value = editBtn.dataset.username || '';
            document.getElementById('editEmail').value = editBtn.dataset.email || '';
            document.getElementById('editPhone').value = editBtn.dataset.phone || '';
            document.getElementById('editAddress').value = editBtn.dataset.address || '';

            // Reset mật khẩu mới (ẩn ô nhập mật khẩu nếu có)
            const editNewPasswordInput = document.getElementById('editNewPassword');
            const generatePasswordBtn = document.getElementById('generatePasswordBtn');
            if (editNewPasswordInput && generatePasswordBtn) {
                editNewPasswordInput.style.display = 'none';
                editNewPasswordInput.value = '';
                generatePasswordBtn.textContent = 'Cấp mật khẩu mới';
            }
        }
    });

    // ✅ Khi bấm "Cấp mật khẩu mới" thì show ô nhập
    const generatePasswordBtn = document.getElementById('generatePasswordBtn');
    const editNewPasswordInput = document.getElementById('editNewPassword');

    if (generatePasswordBtn && editNewPasswordInput) {
        generatePasswordBtn.addEventListener('click', function() {
            if (editNewPasswordInput.style.display === 'none') {
                editNewPasswordInput.style.display = 'block';
                editNewPasswordInput.focus();
                generatePasswordBtn.textContent = 'Hủy cấp mật khẩu';
            } else {
                editNewPasswordInput.style.display = 'none';
                editNewPasswordInput.value = '';
                generatePasswordBtn.textContent = 'Cấp mật khẩu mới';
            }
        });
    }

    // ✅ Xử lý submit form sửa khách hàng
    document.getElementById("editCustomerForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('ajax/update_khachhang.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCustomerModal'));
                    modal.hide();
                    loadCustomers(1, currentFilterParams);
                    alert('Cập nhật khách hàng thành công!');
                } else {
                    alert(data.message || 'Cập nhật khách hàng thất bại!');
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi khi cập nhật khách hàng!');
            });
    });
</script>