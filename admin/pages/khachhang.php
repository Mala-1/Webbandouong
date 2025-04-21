<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .search-container {
        display: flex;
        align-items: center;
        margin-right: 400px;
    }

    .search-container input {
        max-width: 250px;
    }

    .search-container i {
        color: #555;
        font-size: 18px;
        cursor: pointer;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        /* Đảm bảo căn giữa theo chiều dọc */
        width: 100%;
        /* Đảm bảo pagination chiếm toàn bộ chiều rộng container */
        margin: 20px 0;
        /* Khoảng cách trên/dưới */
        padding: 0;
        /* Xóa padding mặc định */
        list-style: none;
        /* Xóa kiểu danh sách mặc định */
    }

    .pagination .page-item {
        margin: 0 5px;
        /* Khoảng cách giữa các nút */
    }

    .pagination .page-link {
        padding: 8px 12px;
        /* Kích thước nút hợp lý */
        border-radius: 5px;
        /* Bo góc nhẹ */
    }

    /* Đảm bảo container cha không làm lệch */
    nav {
        text-align: center;
        /* Fallback cho các trình duyệt cũ */
        width: 100%;
    }

    /* Container chính */
    .container-fluid {
        padding: 0;
        height: 100vh;
    }

    /* Thanh Sidebar */
    .sidebar {
        width: 250px;
        /* Chiều rộng sidebar */
        min-width: 200px;
        transition: width 0.3s;
    }

    /* Nội dung chính */
    .main-content {
        padding: 20px;
        flex-grow: 1;
        /* Chiếm toàn bộ không gian còn lại */
        overflow-x: auto;
    }

    /* Thu hẹp các cột trong bảng */
    .table th.col-id,
    .table td.col-id {
        min-width: 50px;
        /* Giảm từ 60px */
    }

    .table th.col-name,
    .table td.col-name {
        min-width: 120px;
        /* Giảm từ 150px */
    }

    .table th.col-email,
    .table td.col-email {
        min-width: 150px;
        /* Giảm từ 200px */
    }

    .table th.col-phone,
    .table td.col-phone {
        min-width: 120px;
        /* Giảm từ 150px */
    }

    .table th.col-address,
    .table td.col-address {
        min-width: 150px;
        /* Giảm từ 200px */
    }

    .table th.col-status,
    .table td.col-status {
        min-width: 100px;
        /* Giảm từ 120px */
    }

    .table th.col-actions,
    .table td.col-actions {
        min-width: 120px;
        /* Giảm từ 150px */
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
            /* Thu hẹp sidebar trên màn hình nhỏ */
        }

        .main-content {
            padding: 10px;
        }

        .table th,
        .table td {
            font-size: 12px;
            /* Giảm kích thước chữ */
            padding: 8px;
            /* Giảm padding */
        }

        /* Ẩn sidebar nếu cần trên màn hình rất nhỏ */
        @media (max-width: 576px) {
            .sidebar {
                display: none;
                /* Ẩn sidebar */
            }

            .main-content {
                width: 100%;
            }
        }
    }
    </style>
</head>

<body>
    <div class="main-content">
        <h1>Quản Lý Khách Hàng</h1>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal"
                onclick="openAddModal()">+ Thêm Khách Hàng</button>
            <div class="d-flex align-items-center search-container">
                <input type="text" class="form-control me-2" id="searchInput" placeholder="Tìm kiếm tên khách hàng"
                    onkeyup="searchCustomers()">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="customerTable">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">Username</th>
                        <th class="col-email">Mật khẩu</th>
                        <th class="col-email">Email</th>
                        <th class="col-phone">Điện Thoại</th>
                        <th class="col-address">Địa Chỉ</th>
                        <th class="col-status">Trạng thái</th>
                        <th class="col-actions">Chức Năng</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu khách hàng sẽ được chèn tại đây -->
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination" id="pagination">
                <!-- Liên kết phân trang sẽ được chèn tại đây -->
            </ul>
        </nav>
    </div>

    <!-- Modal Khách Hàng -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Thêm Khách Hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm">
                        <input type="hidden" id="customerId">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Điện Thoại</label>
                            <input type="text" class="form-control" id="phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" id="address">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng Thái</label>
                            <select class="form-control" id="status">
                                <option value="active">Hoạt Động</option>
                                <option value="inactive">Không Hoạt Động</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="saveCustomer()">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let currentPage = 1;
    const perPage = 10;
    const totalPages = 32;

    function fetchCustomers(page = 1, search = '') {
        const customers = [{
                id: 1,
                username: 'Nguyễn Văn A',
                password: '123456',
                email: 'a@example.com',
                phone: '0123456789',
                address: '123 Đường ABC',
                status: 'active'
            },
            {
                id: 2,
                username: 'Trần Thị B',
                password: '987654',
                email: 'b@example.com',
                phone: '0987654321',
                address: '456 Đường XYZ',
                status: 'inactive'
            }
        ];
        renderCustomers(customers);
        renderPagination();
    }

    function renderCustomers(customers) {
        const tbody = document.querySelector('#customerTable tbody');
        tbody.innerHTML = '';
        customers.forEach(customer => {
            const row = document.createElement('tr');
            row.innerHTML = `
                     <td class="col-id">${customer.id}</td>
                     <td class="col-name">${customer.username}</td>
                     <td class="col-email">${customer.password}</td>
                     <td class="col-email">${customer.email}</td>
                     <td class="col-phone">${customer.phone}</td>
                     <td class="col-address">${customer.address}</td>
                     <td class="col-status"><span class="status-badge status-${customer.status}">${customer.status === 'active' ? 'Hoạt Động' : 'Không Hoạt Động'}</span></td>
                     <td class="col-actions action-buttons">
                         <button class="btn btn-sm btn-warning" onclick="openEditModal(${customer.id})"><i class="fas fa-edit"></i></button>
                         <button class="btn btn-sm btn-danger" onclick="deleteCustomer(${customer.id})"><i class="fas fa-trash"></i></button>
                     </td>
                 `;
            tbody.appendChild(row);
        });
    }

    function renderPagination() {
        const pagination = document.querySelector('#pagination');
        pagination.innerHTML = '';
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML =
            `<a class="page-link" href="#" onclick="changePage(${currentPage - 1})"><i class="fas fa-chevron-left"></i></a>`;
        pagination.appendChild(prevLi);
        const pageInfo = document.createElement('li');
        pageInfo.className = 'page-item disabled';
        pageInfo.innerHTML = `<a class="page-link">Trang ${currentPage} / ${totalPages}</a>`;
        pagination.appendChild(pageInfo);
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML =
            `<a class="page-link" href="#" onclick="changePage(${currentPage + 1})"><i class="fas fa-chevron-right"></i></a>`;
        pagination.appendChild(nextLi);
    }

    function changePage(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        fetchCustomers(page, document.querySelector('#searchInput').value);
    }

    function searchCustomers() {
        const searchTerm = document.querySelector('#searchInput').value;
        fetchCustomers(1, searchTerm);
    }

    function openAddModal() {
        document.querySelector('#modalTitle').textContent = 'Thêm Khách Hàng';
        document.querySelector('#customerForm').reset();
        document.querySelector('#customerId').value = '';
    }

    function openEditModal(id) {
        const customer = {
            id: id,
            username: 'Nguyễn Văn A',
            password: '********',
            email: 'a@example.com',
            phone: '0123456789',
            address: '123 Đường ABC',
            status: 'active'
        };
        document.querySelector('#modalTitle').textContent = 'Chỉnh Sửa Khách Hàng';
        document.querySelector('#customerId').value = customer.id;
        document.querySelector('#username').value = customer.username;
        document.querySelector('#password').value = customer.password;
        document.querySelector('#email').value = customer.email;
        document.querySelector('#phone').value = customer.phone;
        document.querySelector('#address').value = customer.address;
        document.querySelector('#status').value = customer.status;
        new bootstrap.Modal(document.querySelector('#customerModal')).show();
    }

    function saveCustomer() {
        const id = document.querySelector('#customerId').value;
        const username = document.querySelector('#username').value;
        const password = document.querySelector('#password').value;
        const email = document.querySelector('#email').value;
        const phone = document.querySelector('#phone').value;
        const address = document.querySelector('#address').value;
        const status = document.querySelector('#status').value;

        if (username && password && email) {
            bootstrap.Modal.getInstance(document.querySelector('#customerModal')).hide();
            fetchCustomers(currentPage);
        } else {
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
        }
    }

    function deleteCustomer(id) {
        if (confirm('Bạn có chắc chắn muốn xóa khách hàng này không?')) {
            fetchCustomers(currentPage);
        }
    }

    fetchCustomers();
    </script>
</body>

</html>