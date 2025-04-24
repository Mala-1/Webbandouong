<?php
session_start(); // Start session to store cart data
require_once '../includes/DBConnect.php'; // Kết nối DB
$db = DBConnect::getInstance();

$cartItems  = [];
$grandTotal = 0;

$userId = $_SESSION['user_id'] ?? null;

$sql = 'SELECT 
    c.cart_id,
    c.user_id,
    c.created_at,
    cd.cart_detail_id,
    cd.packaging_option_id,
    cd.quantity,
    cd.price,
    cd.total_price as total,
    p.product_id,
    p.name AS product_name,
    po.packaging_type,
    po.unit_quantity,
    COALESCE(
        po.image,
        (
            SELECT pi.image
            FROM product_images pi
            WHERE pi.product_id = p.product_id
            ORDER BY pi.image ASC
            LIMIT 1
        )
    ) AS image
FROM cart c
JOIN cart_details cd ON c.cart_id = cd.cart_id
LEFT JOIN packaging_options po ON cd.packaging_option_id = po.packaging_option_id
LEFT JOIN products p ON po.product_id = p.product_id
WHERE c.user_id = ? -- truyền user_id nếu cần lọc theo người mua
ORDER BY c.created_at DESC;';

$cartItems = $db->select($sql, [$userId]);

function formatProductName($packaging_type, $unit_quantity, $product_name)
{
    $packaging = trim($packaging_type . ' ' . $unit_quantity);
    // Loại trùng nếu packaging_type đã nằm trong unit_quantity
    if (stripos($unit_quantity, $packaging_type) !== false) {
        $packaging = $unit_quantity;
    }

    // Với unit_quantity là "1 lon", "1 chai", có thể tối giản
    if (preg_match('/^1\s+[[:alpha:]]+$/u', $unit_quantity)) {
        $packaging = $packaging_type; // chỉ in "Lon" hoặc "Chai"
    }

    return "{$packaging} {$product_name}";
}

// Ensure unique packaging_option_id for each product
// $uniqueCartItems = [];
// foreach ($cartItems as $item) {
//     $uniqueKey = $item['product_name'] . '-' . $item['cart_detail_id'];
//     if (!isset($uniqueCartItems[$uniqueKey])) {
//         $item['total'] = $item['price'] * $item['quantity'];
//         $grandTotal += $item['total'];
//         $uniqueCartItems[$uniqueKey] = $item;
//     }
// }
// $cartItems = array_values($uniqueCartItems);

if (!empty($cartItems)) {
    $_SESSION['cartItems'] = $cartItems;
    $_SESSION['grandTotal'] = $grandTotal;
}

// Fetch orders for the user
$orderSql = 'SELECT 
    o.order_id,
    o.status,
    o.total_price,
    o.created_at
FROM orders o
WHERE o.user_id = ?
ORDER BY o.created_at DESC;';

$orders = $db->select($orderSql, [$userId]);
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
    <?php include '../includes/header.php'; ?>

    <div class="container mt-3">
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
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_items[]" value="<?= $item['cart_detail_id'] ?>"></td>
                                <td><img src="../assets/images/SanPham/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 50px; height: auto;"></td>
                                <td class="text-capitalize"><?= formatProductName($item['packaging_type'], $item['unit_quantity'], $item['product_name']) ?></td>
                                <td class="productPrice"><?= number_format($item['price']) ?></td>
                                <td>
                                    <div class="quantity-controls">
                                        <button type="button" class="btn btn-secondary decreaseQuantity">-</button>
                                        <input type="number" class="productQuantity" value="<?= $item['quantity'] ?>" min="1" data-cart-detail-id="<?= $item['cart_detail_id'] ?>">
                                        <button type="button" class="btn btn-secondary increaseQuantity">+</button>
                                    </div>
                                </td>
                                <td class="row-total"><?= number_format($item['total']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-danger deleteProduct">Xóa</button>
                                </td>
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

    <div class="container mt-5">
        <h2>Đơn hàng</h2>
        <?php if (empty($orders)): ?>
            <p>Bạn chưa có đơn hàng nào.</p>
        <?php else: ?>
            <table id="ordersTable">
                <thead>
                    <tr>
                        <th>ID Đơn hàng</th>
                        <th>Tình trạng</th>
                        <th>Giá (VNĐ)</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><?= number_format($order['total_price']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td>
                                <a href="order_detail.php?order_id=<?= $order['order_id'] ?>" class="btn btn-primary">Xem chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseButtons = document.querySelectorAll('.decreaseQuantity');
            const increaseButtons = document.querySelectorAll('.increaseQuantity');
            const deleteButtons = document.querySelectorAll('.deleteProduct');
            const quantityInputs = document.querySelectorAll('.productQuantity');

            decreaseButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    const quantityInput = quantityInputs[index];
                    const cartDetailId = quantityInput.dataset.cartDetailId;
                    if (quantityInput.value > 1) {
                        const newQuantity = parseInt(quantityInput.value) - 1;
                        updateCart('update', cartDetailId, newQuantity, quantityInput);
                    }
                });
            });

            increaseButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    const quantityInput = quantityInputs[index];
                    const cartDetailId = quantityInput.dataset.cartDetailId;
                    const newQuantity = parseInt(quantityInput.value) + 1;
                    updateCart('update', cartDetailId, newQuantity, quantityInput);
                });
            });

            deleteButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const row = button.closest('tr');
                    const cartDetailId = row.querySelector('.productQuantity').dataset.cartDetailId;
                    updateCart('delete', cartDetailId, null, null, row);
                });
            });

            function updateCart(action, cartDetailId, quantity, quantityInput, row = null) {
                fetch('../ajax for cart/update_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action,
                            cart_detail_id: cartDetailId,
                            quantity
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (action === 'update' && quantityInput) {
                                quantityInput.value = quantity;
                                updateRowTotal(quantityInput.closest('tr'));
                                updateGrandTotal();
                            } else if (action === 'delete' && row) {
                                row.remove();
                                updateGrandTotal();
                            }
                        } else {
                            alert(data.message || 'Không thể cập nhật giỏ hàng. Vui lòng thử lại.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                    });
            }

            function updateRowTotal(row) {
                const quantity = parseInt(row.querySelector('.productQuantity').value);
                const price = parseFloat(row.querySelector('.productPrice').textContent.replace(/,/g, ''));
                const rowTotal = row.querySelector('.row-total');
                rowTotal.textContent = (quantity * price).toLocaleString();
            }

            function updateGrandTotal() {
                const rows = document.querySelectorAll('#cartTable tbody tr');
                let grandTotal = 0;

                rows.forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (checkbox && checkbox.checked) {
                        const rowTotal = row.querySelector('.row-total');
                        if (rowTotal) {
                            grandTotal += parseInt(rowTotal.textContent.replace(/,/g, ''));
                        }
                    }
                });

                const totalElement = document.getElementById('grandTotal');
                totalElement.textContent = grandTotal.toLocaleString();
            }

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            const totalElement = document.getElementById('grandTotal');

            function updateGrandTotal() {
                let grandTotal = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const row = checkbox.closest('tr');
                        const rowTotal = parseInt(row.querySelector('.row-total').textContent.replace(/,/g, ''));
                        grandTotal += rowTotal;
                    }
                });
                totalElement.textContent = grandTotal.toLocaleString();
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateGrandTotal);
            });

            // Reset total to 0 on page load
            updateGrandTotal();
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.classList.contains('productQuantity')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>