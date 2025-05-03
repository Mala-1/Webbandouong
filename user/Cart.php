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
$sql = "SELECT SQL_CALC_FOUND_ROWS o.*, pm.name AS payment_method_name, u.username AS username,
        GROUP_CONCAT(
          CONCAT(
            p.product_id, '||', po.packaging_option_id, '||', p.name, '||', op.quantity, '||', op.price, '||', po.packaging_type, '||', po.unit_quantity
          ) SEPARATOR '##'
        ) AS product_details 
        FROM orders o
        LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
        LEFT JOIN order_details op ON o.order_id = op.order_id
        LEFT JOIN products p ON op.product_id = p.product_id
        LEFT JOIN packaging_options po ON op.packaging_option_id = po.packaging_option_id
        LEFT JOIN users u ON o.user_id = u.user_id
        WHERE u.user_id = ?
        GROUP BY o.order_id DESC";

$orders = $db->select($sql, [$userId]);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>

<?php include '../includes/header.php'; ?>

<body>

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
                                <td><input type="checkbox" name="selected_items[]" value="<?= $item['cart_detail_id'] ?>" checked></td>
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

    <?php if ($userId): ?>
        <div class="container mt-5">
            <h2>Đơn hàng</h2>
            <?php if (empty($orders)): ?>
                <p>Bạn chưa có đơn hàng nào.</p>
            <?php else: ?>
                <table id="ordersTable" class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Đơn hàng</th>
                            <th>Tình trạng</th>
                            <th>Giá (VNĐ)</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php foreach ($orders as $order):
                            $orderDetails = [
                                'customer_name' => htmlspecialchars($order['username']),
                                'status' => htmlspecialchars($order['status']),
                                'payment_method' => htmlspecialchars($order['payment_method_name']),
                                'order_date' => htmlspecialchars($order['created_at']),
                                'total_price' => number_format($order['total_price'], 0, ',', '.'),
                                'delivery_address' => htmlspecialchars($order['shipping_address']),
                                'products' => array_map(function ($productDetail) {
                                    $details = explode('||', $productDetail);
                                    return [
                                        'product_id' => $details[0] ?? null,
                                        'packaging_option_id' => $details[1] ?? null,
                                        'name' => $details[2] ?? '',
                                        'quantity' => $details[3] ?? 0,
                                        'price' => (int) $details[4] ?? 0,
                                        'packaging_type' => $details[5] ?? '',
                                        'unit_quantity' => $details[6] ?? ''
                                    ];
                                }, explode('##', $order['product_details'] ?? ''))
                            ];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($order['order_id']) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td><?= number_format($order['total_price']) ?></td>
                                <td><?= htmlspecialchars($order['created_at']) ?></td>
                                <td>
                                    <button class="btn btn-primary btn-view-order"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderDetailsModal"
                                        data-order-details='<?= json_encode($orderDetails) ?>'>
                                        Xem chi tiết
                                    </button>
                                    <?php if ($order['status'] == 'Chờ xử lý'): ?>
                                        <a href="../ajax/delete_order.php?order_id=<?= $order['order_id'] ?>" class="btn btn-danger"
                                            onclick="return confirm('Bạn có chắc chắn muốn huỷ đơn hàng này không?');">
                                            Hủy đơn</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Modal chi tiết đơn hàng -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Chi Tiết Đơn Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div id="orderInfo">
                        <p><strong>Người đặt:</strong> <span id="orderUser">...</span></p>
                        <p><strong>Trạng thái:</strong> <span id="orderStatus">...</span></p>
                        <p><strong>PT thanh toán:</strong> <span id="orderPayment">...</span></p>
                        <p><strong>Ngày đặt:</strong> <span id="orderDate">...</span></p>
                        <p><strong>Tổng giá:</strong> <span id="orderTotal">...</span></p>
                        <p><strong>Địa chỉ giao hàng:</strong> <span id="orderAddress">...</span></p>
                    </div>
                    <h5>Chi tiết sản phẩm đặt hàng</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Kiểu đóng gói</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsBody">
                            <!-- Sản phẩm sẽ được thêm động bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnExportOrderPdf">
                        <i class="fa-solid fa-file-pdf me-1"></i> Xuất PDF
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
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
                if (totalElement) {
                    totalElement.textContent = grandTotal.toLocaleString();
                }

            }

            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const newQuantity = parseInt(this.value);
                    const cartDetailId = this.dataset.cartDetailId;

                    if (newQuantity > 0) {
                        updateCart('update', cartDetailId, newQuantity, this);
                    } else {
                        alert('Số lượng phải lớn hơn 0');
                        this.value = 1;
                        updateCart('update', cartDetailId, 1, this);
                    }
                });
            });

            const orderContainer = document.querySelector("#ordersTable");

            // Sử dụng event delegation
            orderContainer.addEventListener("click", (event) => {
                // Kiểm tra xem phần tử được click có phải là nút "btn-view-order"
                if (event.target.classList.contains("btn-view-order")) {
                    // Lấy dữ liệu chi tiết đơn hàng từ data-order-details
                    const orderDetails = JSON.parse(event.target.getAttribute("data-order-details"));

                    // Render dữ liệu vào modal
                    renderOrderDetails(orderDetails);
                }
            });

            function renderOrderDetails(order) {
                // Gán thông tin đơn hàng
                document.getElementById("orderUser").textContent = order.customer_name;
                document.getElementById("orderStatus").textContent = order.status;
                document.getElementById("orderPayment").textContent = order.payment_method;
                document.getElementById("orderDate").textContent = order.order_date;
                document.getElementById("orderTotal").textContent = `${order.total_price} VNĐ`;
                document.getElementById("orderAddress").textContent = order.delivery_address;

                // Xóa sản phẩm cũ
                const tbody = document.getElementById("orderItemsBody");
                tbody.innerHTML = "";

                // Render từng sản phẩm
                order.products.forEach(product => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
            <td>${product.name}</td>
            <td class="text-capitalize">${product.packaging_type} - ${product.unit_quantity}</td>
            <td>${product.quantity}</td>
            <td>${parseInt(product.price).toLocaleString('vi-VN')} VNĐ</td>
        `;
                    tbody.appendChild(row);
                });

                // Hiển thị/ẩn nút Xuất PDF
                const exportBtn = document.getElementById('btnExportOrderPdf');
                if (order.status === 'Đã xác nhận' || order.status === 'Đã giao hàng') {
                    exportBtn.style.display = 'inline-flex';
                } else {
                    exportBtn.style.display = 'none';
                }
            }
            const form = document.querySelector('form[action="checkOut.php"]');

            form.addEventListener("submit", function(e) {
                e.preventDefault(); // Ngăn submit mặc định

                const selectedItems = [];
                document.querySelectorAll('input[name="selected_items[]"]:checked').forEach(cb => {
                    const row = cb.closest("tr");
                    const cartDetailId = cb.value;
                    const quantity = row.querySelector(".productQuantity").value;

                    selectedItems.push({
                        cart_detail_id: cartDetailId,
                        quantity: parseInt(quantity)
                    });
                });

                if (selectedItems.length === 0) {
                    alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán.");
                    return;
                }

                fetch("../ajax/check_cart_stock.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            items: selectedItems
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            form.submit(); 
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Lỗi kiểm tra tồn kho:", error);
                        alert("Đã xảy ra lỗi khi kiểm tra tồn kho.");
                    });
            });


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
                if (totalElement) {
                    totalElement.textContent = grandTotal.toLocaleString();
                }

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