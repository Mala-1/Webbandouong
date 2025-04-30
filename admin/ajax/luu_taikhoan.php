<?php
header('Content-Type: application/json');
session_start();
require_once '../../includes/DBConnect.php';
$db = DBConnect::getInstance();

$userId = $_SESSION['admin_id'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);

    try {
        $db->execute("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE user_id = ?", [
            $username, $email, $phone, $address, $userId
        ]);

        echo json_encode(['success' => true, 'message' => 'Cập nhật thành công']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật: ' . $e->getMessage()]);
    }
}
?>
