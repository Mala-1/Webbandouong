<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đồ Uống Pro</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>
    <?php if (isset($_SESSION['login_success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['login_success'] ?>
        </div>
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>
    <?php include 'includes/header.php'; ?>

    <!-- Nội dung trang -->
    <?php include 'user/product.php'; ?>

    <?php include 'includes/footer.php'; ?>

    <!-- Script Bootstrap -->
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>