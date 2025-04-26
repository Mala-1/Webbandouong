<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phân quyền Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        .checkbox-disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .checkbox-enabled {
            accent-color: #0d6efd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-enabled:hover {
            transform: scale(1.1);
        }

        .list-group-item.active-link {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }

        .btn-editing {
            background-color: #ffc107;
            color: black;
        }
    </style>
</head>

<body>
    <?php
    require_once '../includes/DBConnect.php';
    $db = DBConnect::getInstance();

    $roles = $db->select("SELECT * FROM roles WHERE role_id != 1");
    $permissions = $db->select("SELECT * FROM permissions");

    $role_id = isset($_GET['role_id']) ? intval($_GET['role_id']) : null;
    if ($role_id) {
        $role_permissions = $db->select(
            "SELECT permission_id, action FROM role_permission_details WHERE role_id = ?",
            [$role_id]
        );

        $assigned_permissions = [];
        foreach ($role_permissions as $rp) {
            $assigned_permissions[$rp['permission_id']][] = $rp['action'];
        }
    } else {
        $assigned_permissions = [];
    }
    ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Quản lý Phân quyền</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Nhóm nhân viên</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fa-solid fa-plus"></i> Thêm
                    </button>
                </div>
                <ul class="list-group">
                    <?php foreach ($roles as $role): ?>
                        <a href="?page=phanquyen&role_id=<?= $role['role_id'] ?>" class="text-decoration-none <?= ($role['role_id'] == $role_id) ? 'text-white' : '' ?>">
                            <li class="list-group-item <?= ($role['role_id'] == $role_id) ? 'active-link' : '' ?>">
                                <?= htmlspecialchars($role['name']) ?>
                            </li>
                        </a>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Modal Thêm Nhóm Nhân Viên -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="formAddRole" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRoleModalLabel">Thêm nhóm nhân viên mới</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="roleName" class="form-label">Tên nhóm nhân viên:</label>
                                <input type="text" class="form-control" id="roleName" name="role_name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                            <button type="submit" class="btn btn-primary">Lưu nhóm</button>
                        </div>
                    </form>

                </div>
            </div>


            <div class="col-md-8">
                <?php if ($role_id): ?>
                    <div class="d-flex align-items-center mb-3">
                        <h5>Phân quyền cho: <span class="text-primary me-4"><?= htmlspecialchars($roles[array_search($role_id, array_column($roles, 'role_id'))]['name'] ?? 'Nhóm') ?></span></h5>

                        <?php if ($role_id != 2): ?>
                            <button id="deleteRoleBtn" class="btn btn-danger btn-sm">Xoá nhóm</button>
                        <?php endif; ?>
                    </div>
                    <button id="editBtn" class="btn btn-warning btn-editing mb-3">Thay đổi quyền</button>
                    <form id="formSavePermission" method="POST">
                        <input type="hidden" name="role_id" value="<?= $role_id ?>">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Quyền</th>
                                    <th>Đọc</th>
                                    <th>Viết</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permissions as $permission): ?>
                                    <tr>
                                        <td class="text-start"><?= htmlspecialchars($permission['name']) ?></td>
                                        <?php foreach (['read', 'write', 'delete'] as $action): ?>
                                            <td>
                                                <input type="checkbox"
                                                    name="permissions[<?= $permission['permission_id'] ?>][]"
                                                    value="<?= $action ?>"
                                                    <?= in_array($action, $assigned_permissions[$permission['permission_id']] ?? []) ? 'checked' : '' ?>
                                                    class="form-check-input checkbox-disabled">
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-success" disabled id="saveBtn">Lưu Thay Đổi</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info">Hãy chọn một nhóm người dùng để phân quyền.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirmDeleteRoleModal" tabindex="-1" aria-labelledby="confirmDeleteRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-body text-center">
                    <p class="fw-bold fs-5 text-dark">
                        Bạn có chắc chắn muốn xoá nhóm nhân viên này không?
                    </p>
                    <div class="mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-danger" id="btnConfirmDeleteRole">Xoá</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Kích hoạt chỉnh sửa checkbox phân quyền
        document.getElementById("editBtn")?.addEventListener("click", function() {
            document.querySelectorAll(".checkbox-disabled").forEach(cb => {
                cb.classList.remove("checkbox-disabled");
                cb.classList.add("checkbox-enabled");
            });
            document.getElementById("saveBtn").removeAttribute("disabled");
            this.textContent = "Đang chỉnh sửa...";
            this.classList.remove('btn-warning');
            this.classList.add('btn-primary');
        });

        document.getElementById("formAddRole")?.addEventListener('submit', async function(e) {
            e.preventDefault(); // Chặn gửi form mặc định

            const formData = new FormData(this);

            try {
                const res = await fetch('ajax/save_role.php', { // Gửi thủ công tới save_role.php
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    alert('✅ Thêm nhóm nhân viên thành công!');
                    location.reload(); // Reload lại để thấy nhóm mới
                } else {
                    alert('❌ Lỗi: ' + (data.message || 'Không rõ nguyên nhân'));
                }
            } catch (err) {
                console.error('Lỗi hệ thống:', err);
                alert('❌ Lỗi hệ thống: ' + err.message);
            }
        });

        // Khi ấn "Xóa nhóm"
        document.getElementById('deleteRoleBtn')?.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteRoleModal'));
            modal.show();
        });

        // Khi xác nhận xóa
        document.getElementById('btnConfirmDeleteRole')?.addEventListener('click', async function() {
            try {
                const res = await fetch('ajax/delete_role.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'role_id=<?= $role_id ?>'
                });
                const data = await res.json();

                if (data.success) {
                    alert('✅ Xóa nhóm thành công!');
                    location.href = '?page=phanquyen'; // Quay về trang chọn nhóm
                } else {
                    alert('❌ Xóa thất bại: ' + (data.message || 'Không rõ nguyên nhân'));
                }
            } catch (err) {
                console.error('Lỗi khi xoá nhóm:', err);
                alert('❌ Lỗi hệ thống: ' + err.message);
            }
        });
        document.getElementById('formSavePermission')?.addEventListener('submit', async function(e) {
            e.preventDefault(); // Ngăn gửi form mặc định

            const formData = new FormData(this);

            try {
                const res = await fetch('ajax/save_permissions.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    alert('✅ Lưu phân quyền thành công!');
                    location.reload(); // Reload để cập nhật giao diện
                } else {
                    alert('❌ Lỗi: ' + (data.message || 'Không rõ nguyên nhân'));
                }
            } catch (err) {
                console.error('❌ Lỗi hệ thống:', err);
                alert('❌ Lỗi hệ thống: ' + err.message);
            }
        });
    </script>
</body>

</html>