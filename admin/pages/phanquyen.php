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
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .disabled-checkbox {
            pointer-events: none;
            opacity: 0.5;
        }
        .enabled-checkbox {
            accent-color: #007bff; /* Đổi màu checkbox thành xanh dương */
        }
    </style>
</head>
<body>
    <?php
    require_once '../includes/DBConnect.php';
    $db = DBConnect::getInstance();

    // Lấy danh sách nhóm người dùng (chỉ lấy nhóm có role_id != 1)
    $roles = $db->select("SELECT * FROM roles WHERE role_id != 1");

    // Lấy danh sách quyền
    $permissions = $db->select("SELECT * FROM permissions");

    // Lấy danh sách phân quyền theo nhóm
    $role_id = isset($_GET['role_id']) ? intval($_GET['role_id']) : null;
    if ($role_id) {
        $role_permissions = $db->select(
            "SELECT permission_id, action FROM role_permission_details WHERE role_id = ?",
            [$role_id]
        );
        
        // Chuyển danh sách quyền của nhóm thành mảng dễ truy cập
        $assigned_permissions = [];
        foreach ($role_permissions as $rp) {
            $assigned_permissions[$rp['permission_id']][] = $rp['action'];
        }
    } else {
        $assigned_permissions = [];
    }
    ?>

    <div class="container mt-5">
        <h2 class="text-center">Quản lý Phân quyền</h2>
        <div class="row">
            <div class="col-md-4">
                <h4>Nhóm người dùng</h4>
                <ul class="list-group">
                    <?php foreach ($roles as $role): ?>
                        <li class="list-group-item">
                            <a href="?page=phanquyen&role_id=<?= $role['role_id'] ?>"><?= $role['name'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-md-8">
                <h4>Phân quyền</h4>
                <button id="editBtn" class="btn btn-warning mb-3">Thay đổi</button>
                <form action="save_permissions.php" method="POST">
                    <input type="hidden" name="role_id" value="<?= $role_id ?>">
                    <table class="table table-bordered">
                        <thead>
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
                                    <td><?= $permission['name'] ?></td>
                                    <td><input type="checkbox" name="permissions[<?= $permission['permission_id'] ?>][]" value="read" <?= (in_array('read', $assigned_permissions[$permission['permission_id']] ?? [])) ? 'checked' : '' ?> class="disabled-checkbox"></td>
                                    <td><input type="checkbox" name="permissions[<?= $permission['permission_id'] ?>][]" value="write" <?= (in_array('write', $assigned_permissions[$permission['permission_id']] ?? [])) ? 'checked' : '' ?> class="disabled-checkbox"></td>
                                    <td><input type="checkbox" name="permissions[<?= $permission['permission_id'] ?>][]" value="delete" <?= (in_array('delete', $assigned_permissions[$permission['permission_id']] ?? [])) ? 'checked' : '' ?> class="disabled-checkbox"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success" disabled id="saveBtn">Lưu</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("editBtn").addEventListener("click", function() {
            document.querySelectorAll(".disabled-checkbox").forEach(el => {
                el.classList.remove("disabled-checkbox");
                el.classList.add("enabled-checkbox"); // Đổi màu khi được bật
            });
            document.getElementById("saveBtn").removeAttribute("disabled");
        });
    </script>
</body>
</html>