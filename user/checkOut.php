<?php
session_start(); // Start session to retrieve cart data
include '../includes/DBConnect.php';
$db = DBConnect::getInstance();
$conn = $db->getConnection();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo '<p>Vui lòng đăng nhập để tiếp tục thanh toán.</p>';
        exit;
    }

    $selectedIds = $_POST['selected_items'] ?? [];

    if (empty($selectedIds)) {
        echo '<p>Không tìm thấy sản phẩm nào được chọn. Vui lòng quay lại giỏ hàng.</p>';
        exit;
    }

    // Truy vấn lại chi tiết giỏ hàng từ DB dựa vào các ID đã chọn
    $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));

    $sql = "
        SELECT 
            cd.*, 
            p.name AS product_name, 
            COALESCE(
                po.image,
                (
                    SELECT pi.image 
                    FROM product_images pi 
                    WHERE pi.product_id = p.product_id 
                    ORDER BY pi.image ASC 
                    LIMIT 1
                )
            ) AS image,
            po.packaging_type, 
            po.unit_quantity
 
        FROM cart_details cd
        JOIN cart c ON cd.cart_id = c.cart_id
        JOIN packaging_options po ON cd.packaging_option_id = po.packaging_option_id
        JOIN products p ON po.product_id = p.product_id
        WHERE cd.cart_detail_id IN ($placeholders) AND c.user_id = ?
    ";

    $params = array_merge($selectedIds, [$user_id]);
    $cartItems = $db->select($sql, $params);

    // Tính grandTotal
    $grandTotal = array_reduce($cartItems, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);

} else {
    echo '<p>Không tìm thấy thông tin đơn hàng. Vui lòng quay lại giỏ hàng.</p>';
    exit;
}

$user_id = $_SESSION['user_id'];

$user = $db->selectOne('SELECT * FROM users WHERE user_id = ?', [$user_id]);

// Tạo mã QR VietQR động cho Techcombank
$bankCode = "TCB";
$accountNumber = "3845632968"; // Thay bằng số tài khoản của shop
$amount = $grandTotal;
$orderCode = "DH" . time() . "_U" . $user_id;
$qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNumber}-compact2.jpg?amount={$amount}&addInfo={$orderCode}";
?>
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
    <style>

    </style>

</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-8 bg-white">
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
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="order-table">
                                <?php foreach ($cartItems as $item): ?>
                                    <tr>
                                        <td><img src="../assets/images/SanPham/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 50px; height: auto;"></td>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= number_format($item['price'] * $item['quantity']) ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><strong><span id="order-total"><?= number_format($grandTotal) ?></span> VND</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- 2. Form nhập thông tin khách hàng -->
                    <form action="placeOrder.php" method="POST" id="payment-form" novalidate>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required><?= $user['address'] ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10,11}" required value="<?= $user['phone'] ?>">
                            </div>

                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?= $user['email'] ?>">
                            </div>

                            <div class="col-md-12">
                                <label for="phone" class="form-label">Ghi chú</label>
                                <input type="text" class="form-control" id="phone" name="note" value="">
                            </div>
                        </div>

                        <!-- Hidden inputs để gửi thông tin đơn hàng -->
                        <div id="hidden-items"></div>

                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Đặt hàng</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- thanh toán -->
            <div class="col-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h4 class="mb-3">Phương thức thanh toán</h4>

                    <?php
                    // Lấy danh sách phương thức thanh toán từ DB
                    $paymentMethods = $db->select("SELECT * FROM payment_method");
                    ?>

                    <?php foreach ($paymentMethods as $method): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method_id" id="method<?= $method['payment_method_id'] ?>" value="<?= $method['payment_method_id'] ?>" required>
                            <label class="form-check-label" for="method<?= $method['payment_method_id'] ?>">
                                <?= htmlspecialchars($method['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <div class="mt-4">
                        <p><strong>Tổng thanh toán:</strong> <span class="text-danger fs-5"><?= number_format($grandTotal) ?> VND</span></p>
                    </div>
                    <!-- QR Thanh toán qua ngân hàng -->
                    <div id="bankQRWrapper" class="mt-4 text-center" style="display: none;">
                        <h5>Quét mã để chuyển khoản Techcombank</h5>
                        <img src="<?= $qrUrl ?>" alt="QR Thanh toán" class="img-fluid" style="max-width: 250px;">
                        <p class="mt-2 text-muted">
                            Nội dung chuyển khoản: <strong><?= $orderCode ?></strong>
                        </p>
                        <p class="text-muted">Số tiền: <strong><?= number_format($amount) ?> VND</strong></p>

                        <!-- ✅ Nút xác nhận chuyển khoản -->
                        <button id="confirmBankTransferBtn"
                            class="btn btn-outline-success mt-3 fw-bold py-2 px-4"
                            style="opacity: 0.5; pointer-events: none; transition: all 0.3s;"
                            title="Vui lòng chuyển khoản xong mới nhấn xác nhận">
                            <i class="fa-solid fa-circle-check me-2"></i> Tôi đã thanh toán
                        </button>

                    </div>


                </div>
            </div>



        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <!-- <script src="../assets/javascript/checkOut.js"></script> -->
    <script>
        const orderCode = <?= json_encode($orderCode) ?>;
        document.querySelector('#payment-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của form

            // Lấy dữ liệu input
            const address = document.getElementById('address').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('email').value.trim();
            const selectedPayment = document.querySelector('input[name="payment_method_id"]:checked');

            // Kiểm tra các trường bắt buộc
            if (address === '') {
                alert('Vui lòng nhập địa chỉ.');
                document.getElementById('address').focus();
                return;
            }

            if (!/^\d{10,11}$/.test(phone)) {
                alert('Vui lòng nhập số điện thoại hợp lệ (10-11 chữ số).');
                document.getElementById('phone').focus();
                return;
            }

            if (email === '') {
                alert('Vui lòng nhập email.');
                document.getElementById('email').focus();
                return;
            }

            if (!selectedPayment) {
                alert('Vui lòng chọn phương thức thanh toán.');
                return;
            }

            // Lấy dữ liệu từ form
            const formData = new FormData(this);

            formData.append('payment_method_id', selectedPayment.value);

            if (selectedPayment.value === '2') {
                formData.append('order_code', orderCode);
            }


            // Gửi dữ liệu đến placeOrder.php
            fetch('placeOrder.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = "Cart.php";
                    } else {
                        alert(data.message);
                    }
                });
        });

        const addressInput = document.getElementById('address');
        const phoneInput = document.getElementById('phone');
        const emailInput = document.getElementById('email');
        const paymentRadios = document.querySelectorAll('input[name="payment_method_id"]');
        const confirmBankBtn = document.getElementById('confirmBankTransferBtn');
        const submitBtn = document.querySelector('#payment-form button[type="submit"]');

        // Hàm kiểm tra điều kiện
        function checkFormValidity() {
            const address = addressInput.value.trim();
            const phone = phoneInput.value.trim();
            const email = emailInput.value.trim();
            const selectedPayment = document.querySelector('input[name="payment_method_id"]:checked');
            const isBankTransfer = selectedPayment && selectedPayment.value === '2';
            const isBankConfirmed = confirmBankBtn.classList.contains('active');

            const isValid =
                address !== '' &&
                phone !== '' &&
                email !== '' &&
                selectedPayment &&
                (!isBankTransfer || isBankConfirmed);

            // Áp dụng trạng thái
            submitBtn.disabled = !isValid;
            submitBtn.style.opacity = isValid ? '1' : '0.5';
            submitBtn.style.pointerEvents = isValid ? 'auto' : 'none';
        }

        // Gọi khi input thay đổi
        [addressInput, phoneInput, emailInput].forEach(input => {
            input.addEventListener('input', checkFormValidity);
        });

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const qrDiv = document.getElementById('bankQRWrapper');
                const confirmBtn = confirmBankBtn;

                if (this.value === '2') {
                    qrDiv.style.display = 'block';

                    setTimeout(() => {
                        confirmBtn.style.opacity = 1;
                        confirmBtn.style.pointerEvents = 'auto';
                        confirmBtn.removeAttribute('title');
                        checkFormValidity();
                    }, 3000);
                } else {
                    qrDiv.style.display = 'none';
                    confirmBtn.classList.remove('active');
                    confirmBtn.style.opacity = 0.3;
                    confirmBtn.style.pointerEvents = 'none';
                    confirmBtn.setAttribute('title', 'Vui lòng chuyển khoản xong mới nhấn xác nhận');
                    checkFormValidity();
                }
            });
        });

        confirmBankBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            checkFormValidity();
        });

        // Khởi đầu: disable nút submit
        window.addEventListener('DOMContentLoaded', checkFormValidity);
    </script>
</body>

</html>