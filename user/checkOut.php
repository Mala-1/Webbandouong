<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Thanh toán đơn hàng</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../assets/css/checkOut.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="payment-card">
      <h2 class="mb-4 text-center">Chi tiết đơn hàng &amp; Thông tin thanh toán</h2>

      <!-- 1. Bảng chi tiết đơn hàng -->
      <div class="mb-5">
        <h4 class="mb-3">Chi tiết đơn hàng</h4>
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Hình ảnh</th>
              <th>Sản phẩm</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
            </tr>
          </thead>
          <tbody id="order-table">
            <!-- JS sẽ chèn <tr> ở đây -->
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
              <td><strong><span id="order-total">0</span> VND</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- 2. Form nhập thông tin khách hàng -->
      <form action="process_payment.php" method="POST" id="payment-form" novalidate>
        <div class="row g-3">
          <div class="col-12">
            <label for="fullName" class="form-label">Họ và tên</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
          </div>
          <div class="col-12">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10,11}" required>
          </div>
          <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
        </div>

        <!-- Hidden inputs để gửi thông tin đơn hàng lên server -->
        <div id="hidden-items"></div>

        <div class="mt-4 d-grid">
          <button type="submit" class="btn btn-primary btn-lg">Thanh toán</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom JS -->
  <script src="../assets/javascript/checkOut.js"></script>
</body>
</html>
