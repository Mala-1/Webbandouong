<?php
require_once '../includes/config.php'; // Kết nối DB

// Đường dẫn file dữ liệu mẫu
$filePath   = __DIR__ . '/duLieuMau.txt';
$cartItems  = [];
$grandTotal = 0;

if (file_exists($filePath)) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($prodId, $qty) = array_map('trim', explode(',', $line));
        $prodId = (int)$prodId;
        $qty    = (int)$qty;
        if ($prodId <= 0 || $qty <= 0) continue;

        // Lấy thông tin sản phẩm và 1 ảnh đầu tiên từ product_images
        $stmt = $pdo->prepare(
            'SELECT p.product_id AS id, p.name, p.price,
                    pi.image
             FROM products p
             LEFT JOIN product_images pi
               ON pi.product_id = p.product_id
             WHERE p.product_id = ?
             ORDER BY pi.image_id ASC
             LIMIT 1'
        );
        $stmt->execute([$prodId]);
        $product = $stmt->fetch();

        if ($product) {
            // Xây đường dẫn tới file ảnh trong folder images/SanPham
            $product['image_path'] = '../assets/images/SanPham/' . $product['image'];
            $product['quantity']   = $qty;
            $product['total']      = $product['price'] * $qty;
            $grandTotal += $product['total'];
            $cartItems[] = $product;
        }
    }
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
            <table id="cartTable">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>       <!-- Cột hiển thị ảnh -->
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá (VNĐ)</th>
                        <th>Số lượng</th>
                        <th>Tổng (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <!-- Phần hiển thị hình ảnh sản phẩm -->
                            <td>
                                <img
                                  src="<?= htmlspecialchars($item['image_path']) ?>"
                                  alt="<?= htmlspecialchars($item['name']) ?>"
                                  class="product-image"
                                >
                            </td>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Tổng cộng:</td>
                        <td><?= number_format($grandTotal) ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
