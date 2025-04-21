<?php
$permissions = $_SESSION['permissions'] ?? [];
$canReadUser = in_array('read', $permissions['Quản lý nhân viên'] ?? []);
$canWriteUser = in_array('write', $permissions['Quản lý nhân viên'] ?? []);
$canDeleteUser = in_array('delete', $permissions['Quản lý nhân viên'] ?? []);
?>

<div class="p-3 d-flex align-items-center rounded" style="background-color: #f0f0f0; height: 80px;">
    <?php if ($canWriteUser): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-plus me-1"></i> THÊM
        </button>
    <?php endif; ?>

    <!-- Thanh tìm kiếm -->
    <div class="flex-grow-1">
        <form class="d-flex justify-content-center mx-auto" style="max-width: 400px; width: 100%;" role="search">
            <input class="user-search form-control me-2" type="search" placeholder="Tìm kiếm tên người dùng" aria-label="Search" name="user-name">
            <button type="button" class="btn-search btn btn-sm p-0 border-0 bg-transparent">
                <i class="fas fa-search fa-lg"></i>
            </button>
        </form>
    </div>
</div>

<!-- Bảng danh sách nhân viên -->
<div class="table-responsive mt-4 pe-3">
    <table class="table align-middle table-bordered">
        <thead class="table-light text-center">
            <tr>
                <th>ID</th>
                <th>Tài khoản</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Chức vụ</th>
                <?php if ($canWriteUser || $canDeleteUser): ?>
                    <th>Chức năng</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="user-wrap text-center align-middle">
            <!-- Dữ liệu nhân viên sẽ được load từ Ajax -->
        </tbody>
    </table>
</div>
<div class="pagination-wrap"></div>

<!-- Modal Thêm nhân viên -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="userForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm Nhân Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Tài khoản</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">SĐT</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Chức vụ</label>
                    <select name="role_id" class="form-select" required>
                        <option value="1">Quản trị</option>
                        <option value="2">Nhân viên</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer mt-2">
                <button type="submit" class="btn btn-primary">Thêm</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal thêm nhân viên -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Thêm Nhân Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên đăng nhập</label>
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
                    <div class="col-md-6">
                        <label class="form-label">Chức vụ (role)</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">-- Chọn chức vụ --</option>
                            <option value="3">Nhân viên bán hàng</option>
                            <option value="4">Quản lý kho</option>
                            <option value="5">Nhân viên CSKH</option>
                            <!-- Thêm các role khác nếu cần -->
                        </select>
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


<!-- Modal xóa nhân viên -->
<div class="modal fade" id="modalXoaNhanVien" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xoá nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xoá nhân viên có mã <strong id="user-id-display"></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaNhanVien">Xoá</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal sửa nhân viên -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editUserForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Sửa Nhân Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="user_id" id="editUserId">

                    <div class="col-md-6">
                        <label class="form-label">Tên đăng nhập</label>
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

                    <div class="col-md-6">
                        <label class="form-label">Chức vụ</label>
                        <select name="role_id" id="editRole" class="form-select" required>
                            <option value="">-- Chọn --</option>
                            <option value="3">Nhân viên bán hàng</option>
                            <option value="4">Quản lý kho</option>
                            <option value="5">CSKH</option>
                            <!-- Có thể thêm role khác nếu cần -->
                        </select>
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

    // Hàm load danh sách nhân viên
    function loadUsers(page = 1, params = "") {
        const userWrap = document.querySelector('.user-wrap');
        const paginationWrap = document.querySelector('.pagination-wrap');

        fetch('ajax/load_nhanvien.php?page=' + page + params)
            .then(res => res.text())
            .then(data => {
                const parts = data.split('SPLIT');
                userWrap.innerHTML = parts[0] || '';
                paginationWrap.innerHTML = parts[1] || '';
            });
    }

    // Gọi lần đầu khi trang load
    loadUsers(1);

    // Bắt sự kiện phân trang
    document.addEventListener("pagination:change", function(e) {
        const {
            page,
            target
        } = e.detail;

        if (target === "nhanvienpage") {
            loadUsers(page, currentFilterParams);
        }
    });

    // Bắt sự kiện tìm kiếm username
    document.querySelector('input[name="search-username"]').addEventListener('input', function() {
        const searchValue = this.value.trim();
        currentFilterParams = searchValue ? '&search_name=' + encodeURIComponent(searchValue) : '';
        loadUsers(1, currentFilterParams);
    });

    // ✅ Thêm nhân viên
    const addUserForm = document.getElementById("userForm");
    if (addUserForm) {
        addUserForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('ajax/add_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                        modal.hide();
                        loadUsers(1); // reload danh sách
                        this.reset();
                        alert('Thêm nhân viên thành công!');
                    } else {
                        alert(data.message || 'Thêm nhân viên thất bại');
                    }
                });
        });
    }

    // ✅ Xoá nhân viên
    let idDangXoa = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-user');
        if (btn) {
            e.preventDefault();
            idDangXoa = btn.getAttribute('data-id');
            document.getElementById('user-id-display').textContent = idDangXoa;
        }
    });

    document.getElementById('btnXacNhanXoaNhanVien').addEventListener('click', function() {
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
                    loadUsers(1, currentFilterParams);
                    alert('Xóa nhân viên thành công!');
                } else {
                    alert('Xóa thất bại: ' + data.message);
                }
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaNhanVien'));
                modal.hide();
            });
    });

    // ✅ Sửa nhân viên

    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit-user');
        if (editBtn) {
            document.getElementById('editUserId').value = editBtn.dataset.id;
            document.getElementById('editUsername').value = editBtn.dataset.username;
            document.getElementById('editEmail').value = editBtn.dataset.email;
            document.getElementById('editPhone').value = editBtn.dataset.phone;
            document.getElementById('editAddress').value = editBtn.dataset.address;
            document.getElementById('editRole').value = editBtn.dataset.role_id;

            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }
    });

    document.getElementById("editUserForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('ajax/update_user.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                    modal.hide();
                    loadUsers(1, currentFilterParams);
                    alert('Cập nhật nhân viên thành công!');
                } else {
                    alert(data.message || 'Lỗi cập nhật');
                }
            });
    });
</script>