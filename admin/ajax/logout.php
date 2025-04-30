<?php
session_start();
session_unset();
session_destroy();
header("Location: ../admin_login.php"); // hoặc về trang chủ
exit;
?>
