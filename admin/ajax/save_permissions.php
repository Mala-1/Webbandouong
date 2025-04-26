<?php
require_once '../../includes/DBConnect.php';

header('Content-Type: application/json');

try {
    $db = DBConnect::getInstance();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
        exit;
    }

    $role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : 0;
    $permissions = $_POST['permissions'] ?? [];

    if ($role_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Thiếu role_id']);
        exit;
    }

    // Bắt đầu transaction
    $db->getConnection()->beginTransaction();

    // Xoá toàn bộ quyền cũ trước
    $db->execute("DELETE FROM role_permission_details WHERE role_id = ?", [$role_id]);

    // Nếu có quyền mới thì thêm lại
    if (!empty($permissions)) {
        foreach ($permissions as $permission_id => $actions) {
            foreach ($actions as $action) {
                $db->execute(
                    "INSERT INTO role_permission_details (role_id, permission_id, action) VALUES (?, ?, ?)",
                    [$role_id, $permission_id, $action]
                );
            }
        }
    }

    // Commit transaction
    $db->getConnection()->commit();

    echo json_encode(['success' => true, 'message' => 'Cập nhật phân quyền thành công']);
} catch (Exception $e) {
    if ($db->getConnection()->inTransaction()) {
        $db->getConnection()->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
