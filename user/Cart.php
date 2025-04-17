<?php
session_start(); // Start session to store cart data
require_once '../includes/config.php'; // Kết nối DB

$cartItems  = [];
$grandTotal = 0;

// Fetch cart details for user_id = 7
$userId = 7;
$stmt = $pdo->prepare(
    'SELECT cd.cart_detail_id, cd.quantity, CONCAT(po.packaging_type, " ", po.unit_quantity, " ", p.name) AS product_name, po.price, pi.image
     FROM cart c
     JOIN cart_details cd ON c.cart_id = cd.cart_id
     JOIN packaging_options po ON cd.packaging_option_id = po.packaging_option_id
     JOIN products p ON po.product_id = p.product_id
     JOIN product_images pi ON p.product_id = pi.product_id
     WHERE c.user_id = ?'
);
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ensure unique packaging_option_id for each product
$uniqueCartItems = [];
foreach ($cartItems as $item) {
    $uniqueKey = $item['product_name'] . '-' . $item['cart_detail_id'];
    if (!isset($uniqueCartItems[$uniqueKey])) {
        $item['total'] = $item['price'] * $item['quantity'];
        $grandTotal += $item['total'];
        $uniqueCartItems[$uniqueKey] = $item;
    }
}
$cartItems = array_values($uniqueCartItems);

if (!empty($cartItems)) {
    $_SESSION['cartItems'] = $cartItems;
    $_SESSION['grandTotal'] = $grandTotal;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>
    <div class="container">
        <h1>Giỏ hàng</h1>
        <?php if (empty($cartItems)): ?>
            <p>Không có sản phẩm trong giỏ.</p>
        <?php else: ?>
            <form action="checkOut.php" method="POST">
                <table id="cartTable">
                    <thead>
                        <tr>
                            <th>Chọn</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá (VNĐ)</th>
                            <th>Số lượng</th>
                            <th>Tổng (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_items[]" value="<?= $item['cart_detail_id'] ?>"></td>
                                <td><img src="../assets/images/SanPham/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 50px; height: auto;"></td>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= number_format($item['price']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td class="row-total"><?= number_format($item['total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">Tổng cộng:</td>
                            <td id="grandTotal"><?= number_format($grandTotal) ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right">
                                <button type="submit" class="btn btn-primary">Thanh toán</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            const totalElement = document.getElementById('grandTotal');

            function updateTotal() {
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const row = cb.closest('tr');
                        const rowTotal = parseInt(row.querySelector('.row-total').textContent.replace(/,/g, ''));
                        total += rowTotal;
                    }
                });
                totalElement.textContent = total.toLocaleString();
            }

            // Reset total to 0 on page load
            totalElement.textContent = '0';

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });
        });
    </script>
</body>
</html>
