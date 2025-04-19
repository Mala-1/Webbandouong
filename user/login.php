<?php
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['redirect_back'])) {
  if (!isset($_POST['login']) && !isset($_POST['register'])) {
    $_SESSION['redirect_back'] = $_SERVER['HTTP_REFERER'] ?? '../index.php';
  }
}

// Kết nối CSDL
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_douong";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý đăng ký
$message = "";
$role_id = 1; // user

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $dia_chi = $_POST['diachi'];
  $sdt = $_POST['SDT'];

  $stmt = $conn->prepare("INSERT INTO users (username, email, password, address, phone, role_id) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssi", $username, $email, $password, $dia_chi, $sdt, $role_id);  //role 1 là user

  if ($stmt->execute()) {
    $message = "Đăng ký thành công! Vui lòng đăng nhập.";
  } else {
    $message = "Lỗi: " . $stmt->error;
  }
}

// Xử lý đăng nhập
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role_id = ?");
  $stmt->bind_param("si", $username, $role_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];

    // 👇 Lưu thông báo vào session
    $_SESSION['login_success'] = 'Đăng nhập thành công!';

    $redirect = $_SESSION['redirect_back'] ?? '../index.php';
    unset($_SESSION['redirect_back']);
    header("Location: $redirect");
    exit();
  } else {
    $message = "Sai tên đăng nhập hoặc mật khẩu!";
  }
}

// Đăng xuất
if (isset($_GET['logout'])) {
  $redirect = $_GET['redirect'] ?? '../index.php';
  session_destroy();
  header("Location: $redirect");
  exit();
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Đăng nhập / Đăng ký</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/form.css">
</head>

<body>

  <?php if (!isset($_SESSION['username'])): ?>
    <div class="overlay" id="popupForm">
      <div class="popup">
        <span class="close-btn" id="closeForm">×</span>

        <?php if ($message): ?>
          <div class="msg"><?= $message ?></div>
        <?php endif; ?>

        <!-- Form Đăng ký -->
        <form id="registerForm" method="POST" style="display:none;">
          <h2>Đăng ký</h2>
          <div class="form-group"><input type="text" name="username" placeholder="Username" required></div>
          <div class="form-group"><input type="text" name="diachi" placeholder="Địa chỉ" required></div>
          <div class="form-group"><input type="text" name="SDT" placeholder="Số điện thoại" required></div>
          <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
          <div class="form-group"><input type="password" name="password" placeholder="Mật khẩu" required></div>
          <button type="submit" name="register">Đăng ký</button>
          <div class="switch-link">Đã có tài khoản? <a id="switchToLogin">Đăng nhập</a></div>
        </form>

        <!-- Form Đăng nhập -->
        <form id="loginForm" method="POST" style="display:block;">
          <h2>Đăng nhập</h2>
          <div class="form-group"><input type="text" name="username" placeholder="Tên đăng nhập" required></div>
          <div class="form-group"><input type="password" name="password" placeholder="Mật khẩu" required></div>
          <button type="submit" name="login">Đăng nhập</button>
          <div class="switch-link">Chưa có tài khoản? <a id="switchToRegister">Đăng ký</a></div>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <script>
    const redirectBack = "<?= $_SESSION['redirect_back'] ?? '../index.php' ?>";
  </script>
  <script src="../assets/javascript/login.js"></script>
</body>

</html>