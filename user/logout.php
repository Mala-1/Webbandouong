<?php
session_start();
session_destroy(); // Xoá toàn bộ session
header("Location: ../index.php"); // Chuyển hướng về trang chủ
exit();
?>