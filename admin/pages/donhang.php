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
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
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
                    <th scope="col">Phương thức thanh toán</th> <!-- ✅ Thêm cột này -->
                    <th scope="col">Địa chỉ giao hàng</th>
                    <th scope="col">Ngày đặt</th>
                    <?php if ($canWrite || $canDelete): ?>
                        <th scope="col">Chức năng</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody class="order-wrap text-center align-middle">

            </tbody>
        </table>
    </div>
    <div class="pagination-wrap"></div>

</div>


<!-- Modal thêm đơn hàng -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="orderForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOrderModalLabel">Thêm Đơn Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Người đặt hàng</label>
                        <div class="input-group">
                            <input type="number" id="user_id" name="user_id" class="form-control" placeholder="Nhập ID người đặt" required>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#selectUserModal">Chọn khách hàng</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <input type="text" name="shipping_address" class="form-control" placeholder="Nhập địa chỉ" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái đơn hàng</label>
                        <select name="status" class="form-select" required>
                            <option value="Chờ xử lý" selected>Chờ xử lý</option>
                            <option value="Đã xác nhận">Đã xác nhận</option>
                            <option value="Đã giao hàng">Đã giao hàng</option>
                            <option value="Đã hủy">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phương thức thanh toán</label>
                        <select name="payment_method_id" class="form-select">
                            <?php
                            $payment_method = $db->select('SELECT * FROM payment_method', []);
                            ?>
                            <?php foreach ($payment_method as $p): ?>
                                <option value="<?= $p['payment_method_id'] ?>"><?= $p['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Ghi chú đơn hàng</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Nhập ghi chú (nếu có)"></textarea>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chọn khách hàng -->
<div class="modal fade" id="selectUserModal" tabindex="-1" aria-labelledby="selectUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn khách hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Tìm kiếm khách hàng -->
                <input type="text" id="searchUser" class="form-control" placeholder="Tìm kiếm theo username...">
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Chọn</th>
                            </tr>
                        </thead>
                        <tbody id="userTable">
                            <!-- Danh sách khách hàng sẽ được tải động -->
                        </tbody>
                    </table>
                    <div class="pagination-user-wrap"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmUserSelection">Xác nhận</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal sửa đơn hàng -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editOrderForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrderModalLabel">Sửa Đơn Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" id="order_id" name="order_id"> <!-- Để lưu mã đơn hàng -->

                    <div class="col-md-6">
                        <label class="form-label">Người đặt hàng</label>
                        <input type="number" id="user_id" name="user_id" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <input type="text" id="shipping_address" name="shipping_address" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái đơn hàng</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="Chờ xử lý">Chờ xử lý</option>
                            <option value="Đã xác nhận">Đã xác nhận</option>
                            <option value="Đã giao hàng">Đã giao hàng</option>
                            <option value="Đã hủy">Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phương thức thanh toán</label>
                        <select id="payment_method_id" name="payment_method_id" class="form-select">
                            <?php
                            $payment_method = $db->select('SELECT * FROM payment_method', []);
                            ?>
                            <?php foreach ($payment_method as $p): ?>
                                <option value="<?= $p['payment_method_id'] ?>"><?= $p['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Ghi chú đơn hàng</label>
                        <textarea id="note" name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal thêm chi tiết đơn hàng -->
<div class="modal fade" id="addOrderDetailsModal" tabindex="-1" aria-labelledby="addOrderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderDetailsModalLabel">Thêm Chi Tiết Đơn Hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <!-- ✅ Thông tin đơn hàng -->
                <table class="table table-sm table-bordered mb-4">
                    <tbody>
                        <tr>
                            <th style="width: 15%">Mã đơn hàng</th>
                            <td id="info_order_id"></td>
                            <th style="width: 15%">Người đặt</th>
                            <td id="info_user_id"></td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td id="info_status"></td>
                            <th>Tổng giá</th>
                            <td id="info_total_price"></td>
                        </tr>
                        <tr>
                            <th>PT thanh toán</th>
                            <td id="info_payment"></td>
                            <th>Địa chỉ giao hàng</th>
                            <td id="info_address"></td>
                        </tr>
                        <tr>
                            <th>Ngày đặt</th>
                            <td id="info_date" colspan="3"></td>
                        </tr>
                    </tbody>
                </table>

                <!-- ✅ Chi tiết đơn hàng: sản phẩm + kiểu đóng gói -->
                <div class="col-12">
                    <label class="mb-2 fw-bold">Chi tiết sản phẩm đặt hàng</label>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Kiểu đóng gói</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody id="orderDetailsTable">
                            <!-- Dòng chi tiết sẽ được thêm động ở đây -->
                            <tr id="addRowTrigger">
                                <td colspan="6">
                                    <button class="btn btn-success" id="btnAddRow" type="button">
                                        <i class="fa-solid fa-circle-plus"></i> Thêm
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="saveOrderDetails">Lưu chi tiết</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal chọn kiểu đóng gói -->
<div class="modal fade" id="selectPackagingModal" tabindex="-1" aria-labelledby="selectPackagingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn kiểu đóng gói</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- 🔍 Tìm kiếm sản phẩm -->
                <input type="text" id="searchPackaging" class="form-control" placeholder="Tìm theo tên sản phẩm...">

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Kiểu đóng gói</th>
                                <th>Đơn vị</th>
                                <th>Giá</th>
                                <th>Ảnh</th>
                                <th>Chọn</th>
                            </tr>
                        </thead>
                        <tbody id="packagingTable">
                            <!-- Dữ liệu đóng gói sẽ được load vào đây -->
                            <!-- Ví dụ:
              <tr>
                <td>Sữa tươi</td>
                <td>Lốc</td>
                <td>6 chai</td>
                <td>45.000đ</td>
                <td><img src="path.jpg" width="50"></td>
                <td><button class="btn btn-success btn-sm" onclick="selectPackaging(this)">Chọn</button></td>
              </tr>
              -->
                        </tbody>
                    </table>
                </div>
                <div class="pagination-packaging-wrap"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>








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
        if (target === "userpage") {
            console.log('debug');
            loadUsers(page, currentFilterParams);
        }
        if (target === "packagingpage") {
            loadPackagingOptions(page, currentFilterParamsPackaging);
        }


    });

    document.addEventListener("click", function(e) {
        const targetLink = e.target.closest("[data-page][data-target]");
        if (targetLink) {
            e.preventDefault();
            console.log('DEBUG CLICK PAGINATION:', targetLink.dataset.page, targetLink.dataset.target);
            const page = parseInt(targetLink.getAttribute("data-page"));
            const targetName = targetLink.getAttribute("data-target");

            if (!isNaN(page) && targetName) {
                const event = new CustomEvent("pagination:change", {
                    detail: {
                        page,
                        target: targetName
                    }
                });
                document.dispatchEvent(event);
            }
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

    const userInput = document.querySelector("#user_id");
    const searchUser = document.querySelector("#searchUser");
    const userTable = document.querySelector("#userTable");
    const confirmBtn = document.querySelector("#confirmUserSelection");
    const selectUserModal = new bootstrap.Modal(document.querySelector("#selectUserModal"));
    const addOrderModal = new bootstrap.Modal(document.querySelector("#addOrderModal"));
    let selectedUserId = null;
    let selectedRow = null;
    let currentFilterParamsUser = '';

    // 🚀 Tải danh sách khách hàng khi mở modal
    document.querySelector('[data-bs-target="#selectUserModal"]').addEventListener("click", function() {
        loadUsers(1);
    });

    function loadUsers(page = 1, params = "") {
        const paginationWrap = document.querySelector('.pagination-user-wrap');

        fetch('ajax/load_users.php?page=' + page + params)
            .then(res => res.json())
            .then(data => {
                userTable.innerHTML = data.users || '';
                paginationWrap.innerHTML = data.pagination || '';
            });
    }


    // 🎯 Xử lý tìm kiếm khách hàng
    searchUser.addEventListener('input', debounce(function() {
        const searchValue = searchUser.value.trim();
        currentFilterParams = searchValue ? `&search=${encodeURIComponent(searchValue)}` : '';
        loadUsers(1, currentFilterParams);
    }, 300));

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // 🎯 Xử lý chọn khách hàng (chỉ chọn 1 người)
    userTable.addEventListener("click", function(e) {
        if (e.target.classList.contains("select-user")) {
            const row = e.target.closest("tr");

            // Nếu đã có một lựa chọn trước đó, bỏ chọn nó
            if (selectedRow) {
                selectedRow.classList.remove("table-active");
            }

            // Chọn dòng mới
            selectedRow = row;
            selectedUserId = row.dataset.id;
            selectedRow.classList.add("table-active");
        }
    });

    // 🎯 Xác nhận chọn khách hàng → Đóng modal user → Mở lại modal đơn hàng
    confirmBtn.addEventListener("click", function() {
        if (selectedUserId) {
            userInput.value = selectedUserId;
            selectUserModal.hide();
            setTimeout(() => addOrderModal.show(), 500); // Đợi modal user đóng rồi mở lại modal đơn hàng
        }
    });

    // 🎯 Đóng modal user → Quay lại modal đơn hàng
    document.querySelector('#selectUserModal .btn-close').addEventListener("click", function() {
        selectUserModal.hide();
        setTimeout(() => addOrderModal.show(), 500);
    });


    document.addEventListener("DOMContentLoaded", function() {
        const orderForm = document.querySelector("#orderForm");
        const addOrderModal = document.querySelector("#addOrderModal");
        const addOrderDetailsModal = new bootstrap.Modal(document.querySelector("#addOrderDetailsModal"));

        orderForm.addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("ajax/add_order.php", {
                    method: "POST",
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector("#info_order_id").textContent = data.order_id;
                        document.querySelector("#info_user_id").textContent = data.user_id;
                        document.querySelector("#info_status").textContent = data.status;
                        document.querySelector("#info_total_price").textContent = new Intl.NumberFormat('vi-VN').format(data.total_price || 0) + ' VNĐ';
                        document.querySelector("#info_payment").textContent = data.payment_method_name || "Không xác định";
                        document.querySelector("#info_address").textContent = data.shipping_address || "Không có";
                        document.querySelector("#info_date").textContent = data.created_at || "-";

                        // 1. Đóng modal Thêm đơn hàng
                        const modalInstance = bootstrap.Modal.getInstance(addOrderModal);
                        modalInstance.hide();

                        // 2. Reset form nếu cần
                        this.reset();

                        // loadOrderDetails(data.order_id); // Uncomment nếu có hàm
                        addOrderDetailsModal.show();

                        loadOrders(1);
                    } else {
                        alert(data.message);
                    }
                });
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        const editModal = document.querySelector("#editOrderModal");
        const orderIdInput = editModal.querySelector("#order_id");
        const userInput = editModal.querySelector("#user_id");
        const statusSelect = editModal.querySelector("#status");
        const addressInput = editModal.querySelector("#shipping_address");
        const paymentSelect = editModal.querySelector("#payment_method_id");
        const noteInput = editModal.querySelector("#note");

        // 🎯 Sử dụng Event Delegation để xử lý sự kiện click
        document.addEventListener("click", function(event) {
            const editBtn = event.target.closest(".btn-edit-order");
            if (editBtn) {
                // 🚀 Lấy dữ liệu từ `data-attributes`
                orderIdInput.value = editBtn.dataset.id;
                userInput.value = editBtn.dataset.user;
                addressInput.value = editBtn.dataset.address;
                paymentSelect.value = editBtn.dataset.payment;
                noteInput.value = editBtn.dataset.note;

                // 🚀 Đảm bảo trạng thái được chọn đúng
                const statusValue = editBtn.dataset.status.trim();
                Array.from(statusSelect.options).forEach(option => {
                    option.selected = option.value === statusValue;
                });

                // 🚀 Hiển thị modal sửa đơn hàng
                new bootstrap.Modal(editModal).show();
            }
        });

        // 🎯 Xử lý cập nhật đơn hàng thông qua AJAX
        document.getElementById("editOrderForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const order_id = this.order_id.value;
            const status = this.status.value;
            const shipping_address = this.shipping_address.value.trim();
            const payment_method_id = this.payment_method_id.value;
            const note = this.note.value.trim();

            fetch("ajax/update_order.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        order_id,
                        status,
                        shipping_address,
                        payment_method_id,
                        note
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(editModal);
                        modal.hide();
                        loadOrders(1, currentFilterParams);
                        alert("Cập nhật đơn hàng thành công!");
                    } else {
                        alert(data.message || "Lỗi cập nhật đơn hàng");
                    }
                });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(tooltipEl => new bootstrap.Tooltip(tooltipEl));
    });

    document.addEventListener('DOMContentLoaded', function() {
        const btnAddRow = document.getElementById('btnAddRow');
        const tableBody = document.getElementById('orderDetailsTable');

        // ✅ Event delegation cho nút thêm dòng mới
        tableBody.addEventListener("click", function(e) {
            if (e.target && e.target.id === "btnAddRow") {
                const newRow = document.createElement("tr");
                newRow.innerHTML = `
                <td>
                    <input type="hidden" name="product_id[]" value="">
                    <input type="text" class="form-control" name="product_name[]" readonly>
                </td>
                <td class="d-flex align-items-center gap-2 justify-content-center">
                    <input type="text" class="form-control" name="packaging_option[]" readonly>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="openPackagingSelector(this)">Chọn</button>
                </td>
                <td><input type="number" class="form-control" name="quantity[]" placeholder="Số lượng"></td>
                <td><input type="text" class="form-control" name="price[]" readonly></td>
            `;
                tableBody.insertBefore(newRow, document.getElementById("addRowTrigger"));
            }
        });
    });

    let currentTargetRow = null;
    let currentFilterParamsPackaging = "";

    const packagingTable = document.querySelector("#packagingTable");
    const paginationWrap = document.querySelector(".pagination-packaging-wrap");
    const searchPackaging = document.querySelector("#searchPackaging");
    const btnAddRow = document.getElementById("btnAddRow");
    const tableBody = document.getElementById("orderDetailsTable");

    // 🧠 debounce dùng 1 lần cho toàn trang
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // 📦 Load từ DB qua AJAX
    function loadPackagingOptions(page = 1, params = "") {
        fetch(`ajax/load_packaging_options.php?page=${page}${params}`)
            .then(res => res.json())
            .then(data => {
                packagingTable.innerHTML = data.packaging_html || '';
                paginationWrap.innerHTML = data.pagination || '';
            });
    }

    // 🔍 Tìm kiếm gần đúng
    searchPackaging.addEventListener("input", debounce(function() {
        const keyword = searchPackaging.value.trim();
        currentFilterParamsPackaging = keyword ? `&search=${encodeURIComponent(keyword)}` : '';
        loadPackagingOptions(1, currentFilterParamsPackaging);
    }, 300));

    // 🔍 Mở modal chọn đóng gói
    window.openPackagingSelector = function(button) {
        currentTargetRow = button.closest("tr");

        const mainModal = bootstrap.Modal.getInstance(document.getElementById('addOrderDetailsModal'));
        if (mainModal) mainModal.hide();

        const packagingModal = new bootstrap.Modal(document.getElementById('selectPackagingModal'));
        packagingModal.show();

        loadPackagingOptions(1, currentFilterParamsPackaging);
    };

    // ✅ Gán sản phẩm đã chọn
    window.selectPackaging = function(btn) {
        const name = btn.dataset.product;
        const packaging = btn.dataset.packaging;
        const price = btn.dataset.price;
        const id = btn.dataset.productId;

        if (currentTargetRow) {
            currentTargetRow.querySelector('input[name="product_id[]"]').value = id;
            currentTargetRow.querySelector('input[name="product_name[]"]').value = name;
            currentTargetRow.querySelector('input[name="packaging_option[]"]').value = packaging;
            currentTargetRow.querySelector('input[name="price[]"]').value = parseInt(price).toLocaleString();
            currentTargetRow.querySelector('input[name="quantity[]"]').removeAttribute("readonly");
        }

        const packagingModal = bootstrap.Modal.getInstance(document.getElementById('selectPackagingModal'));
        if (packagingModal) packagingModal.hide();

        const addOrderDetailsModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addOrderDetailsModal'));
        setTimeout(() => addOrderDetailsModal.show(), 300);
    };

    // 🚀 Tải mặc định khi mở modal
    document.querySelector('[data-bs-target="#selectPackagingModal"]')?.addEventListener("click", function() {
        loadPackagingOptions(1);
    });


    document.addEventListener("DOMContentLoaded", function() {
        let currentTargetRow = null;
        let currentFilterParamsPackaging = "";

        const packagingTable = document.querySelector("#packagingTable");
        const paginationWrap = document.querySelector(".pagination-packaging-wrap");
        const searchPackaging = document.querySelector("#searchPackaging");
        const btnAddRow = document.getElementById("btnAddRow");
        const tableBody = document.getElementById("orderDetailsTable");

        // 🧠 debounce dùng 1 lần cho toàn trang
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // 📦 Load từ DB qua AJAX
        function loadPackagingOptions(page = 1, params = "") {
            fetch(`ajax/load_packaging_options.php?page=${page}${params}`)
                .then(res => res.json())
                .then(data => {
                    packagingTable.innerHTML = data.packaging_html || '';
                    paginationWrap.innerHTML = data.pagination || '';
                });
        }

        // 🔍 Tìm kiếm gần đúng
        searchPackaging.addEventListener("input", debounce(function() {
            const keyword = searchPackaging.value.trim();
            currentFilterParamsPackaging = keyword ? `&search=${encodeURIComponent(keyword)}` : '';
            loadPackagingOptions(1, currentFilterParamsPackaging);
        }, 300));


        // 🔍 Mở modal chọn đóng gói
        window.openPackagingSelector = function(button) {
            currentTargetRow = button.closest("tr");

            const mainModal = bootstrap.Modal.getInstance(document.getElementById('addOrderDetailsModal'));
            if (mainModal) mainModal.hide();

            const packagingModal = new bootstrap.Modal(document.getElementById('selectPackagingModal'));
            packagingModal.show();

            loadPackagingOptions(1, currentFilterParamsPackaging);
        };

        // ✅ Gán sản phẩm đã chọn
        window.selectPackaging = function(btn) {
            const name = btn.dataset.product;
            const packaging = btn.dataset.packaging;
            const price = btn.dataset.price;
            const id = btn.dataset.productId;

            if (currentTargetRow) {
                currentTargetRow.querySelector('input[name="product_id[]"]').value = id;
                currentTargetRow.querySelector('input[name="product_name[]"]').value = name;
                currentTargetRow.querySelector('input[name="packaging_option[]"]').value = packaging;
                currentTargetRow.querySelector('input[name="price[]"]').value = parseInt(price).toLocaleString();
                currentTargetRow.querySelector('input[name="quantity[]"]').removeAttribute("readonly");
            }

            const packagingModal = bootstrap.Modal.getInstance(document.getElementById('selectPackagingModal'));
            if (packagingModal) packagingModal.hide();

            const addOrderDetailsModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addOrderDetailsModal'));
            setTimeout(() => addOrderDetailsModal.show(), 300);
        };

        // 🚀 Tải mặc định khi mở modal
        document.querySelector('[data-bs-target="#selectPackagingModal"]')?.addEventListener("click", function() {
            loadPackagingOptions(1);
        });
    });
</script>