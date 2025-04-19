<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

// Lấy danh sách đơn hàng
$orders = $db->select("SELECT * FROM orders");


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$permissions = $_SESSION['permissions'] ?? [];
$canWrite = in_array('write', $permissions['Quản lý đơn hàng'] ?? []);
$canDelete = in_array('delete', $permissions['Quản lý đơn hàng'] ?? []);

?>

<div>
    <div class="p-3 d-flex align-items-center rounded" style="background-color: #f0f0f0; height: 80px;">
        <?php if ($canWrite): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemDonHang">
                <i class="fa-solid fa-plus me-1"></i> THÊM
            </button>
        <?php endif; ?>

        <!-- Thanh tìm kiếm -->
        <div class="flex-grow-1">
            <form class="d-flex justify-content-center mx-auto" style="max-width: 400px; width: 100%;" role="search">
                <input class="order-id form-control me-2" type="search" placeholder="Tìm theo mã đơn hàng" aria-label="Search" name="order_id">
                <button type="button" class="btn-search btn btn-sm p-0 border-0 bg-transparent">
                    <i class="fas fa-search fa-lg"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Tìm kiếm nâng cao -->
    <form method="GET" action="" class="form-search d-flex gap-2 align-items-center container mt-3 flex-wrap justify-content-center">
        <input type="number" class="min-price form-control w-auto" style="width: 120px;" name="price_min" placeholder="Tổng giá từ">
        <input type="number" class="max-price form-control w-auto" style="width: 120px;" name="price_max" placeholder="Tổng giá đến">

        <select class="statusSearch form-select w-auto" style="width: 180px;" name="status">
            <option value="">Tất cả trạng thái</option>
            <option value="Chờ xử lý">Chờ xử lý</option>
            <option value="Đã xác nhận">Đã xác nhận</option>
            <option value="Đã giao hàng">Đã giao hàng</option>
            <option value="Đã hủy">Đã hủy</option>
        </select>
    </form>

    <!-- Bảng danh sách đơn hàng -->
    <div class="table-responsive mt-4 pe-3">
        <table class="table align-middle table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th scope="col">Mã đơn hàng</th>
                    <th scope="col">Người đặt</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Tổng giá</th>
                    <th scope="col">Địa chỉ giao hàng</th>
                    <th scope="col">Ngày đặt</th>
                    <?php if ($canWrite || $canDelete): ?>
                        <th scope="col">Chức năng</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="order-wrap text-center align-middle">
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= $order['user_id'] ?></td>
                        <td><?= $order['status'] ?></td>
                        <td><?= number_format($order['total_price'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= htmlspecialchars($order['shipping_address'] ?? 'Không có') ?></td>
                        <td><?= $order['created_at'] ?></td>
                        <?php if ($canWrite || $canDelete): ?>
                            <td>
                                <?php if ($canWrite): ?>
                                    <i class="fas fa-pen text-primary btn-edit-order me-3 fa-lg" style="cursor: pointer;"
                                        data-id="<?= $order['order_id'] ?>"
                                        data-status="<?= htmlspecialchars($order['status']) ?>"
                                        data-total="<?= $order['total_price'] ?>"></i>
                                <?php endif; ?>

                                <?php if ($canDelete): ?>
                                    <i class="fas fa-trash fa-lg text-danger btn-delete-order" style="cursor: pointer;" data-id="<?= $order['order_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaDonHang"></i>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap"></div>

    <script>
        let currentFilterParams = "";

        function loadOrders(page = 1, params = "") {
            const orderWrap = document.querySelector('.order-wrap');
            const paginationWrap = document.querySelector('.pagination-wrap');

            fetch('ajax/load_orders.php?page=' + page + params)
                .then(res => res.text())
                .then(data => {
                    const parts = data.split('SPLIT');
                    orderWrap.innerHTML = parts[0] || '';
                    paginationWrap.innerHTML = parts[1] || '';
                });
        }

        // Tải danh sách đơn hàng khi trang được load
        loadOrders(1);

        document.addEventListener("pagination:change", function(e) {
            const {
                page,
                target
            } = e.detail;

            if (target === "orderpage") {
                loadOrders(page, currentFilterParams);
            }
        });

        // 🎯 Lắng nghe sự kiện tìm kiếm bằng input, keypress, Enter
        document.querySelectorAll('.form-search input, .form-search select').forEach(element => {
            element.addEventListener('input', debounce(handleFilterChange, 300));
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleFilterChange();
                }
            });
            element.addEventListener('keyup', function(e) {
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    setTimeout(handleFilterChange, 300);
                }
            });
        });

        // 🎯 Xử lý sự kiện `change` cho thẻ `<select>` trạng thái đơn hàng
        document.querySelector('.statusSearch').addEventListener('change', handleFilterChange);

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        function handleFilterChange() {
            const orderId = document.querySelector('.order-id').value.trim();
            const priceMin = document.querySelector('.min-price').value.trim();
            const priceMax = document.querySelector('.max-price').value.trim();
            const status = document.querySelector('.statusSearch').value.trim();

            currentFilterParams = "";

            if (orderId) currentFilterParams += `&search_id=${encodeURIComponent(orderId)}`;
            if (priceMin) currentFilterParams += `&price_min=${encodeURIComponent(priceMin)}`;
            if (priceMax) currentFilterParams += `&price_max=${encodeURIComponent(priceMax)}`;
            if (status) currentFilterParams += `&status=${encodeURIComponent(status)}`;

            loadOrders(1, currentFilterParams);
        }
    </script>
</div>