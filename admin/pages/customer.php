<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .container-fluid {
        padding: 0;
        overflow-x: hidden;
    }

    .sidebar {
        background-color: #f8f9fa;
        padding: 20px;
        height: 100vh;
        width: 250px;
        min-width: 200px;
        max-width: 20%;
        position: fixed;
    }

    .main-content {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
    }

    .sidebar h4 {
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
    }

    .status-active {
        background-color: green;
    }

    .status-inactive {
        background-color: red;
    }

    .table-responsive {
        width: 100%;
        overflow-x: hidden;
    }

    .table {
        width: 100%;
        table-layout: auto;
    }

    .table th,
    .table td {
        padding: 10px;
        white-space: nowrap;
    }

    .table th.col-id,
    .table td.col-id {
        width: auto;
        min-width: 60px;
    }

    .table th.col-name,
    .table td.col-name {
        width: auto;
        min-width: 150px;
    }

    .table th.col-email,
    .table td.col-email {
        width: auto;
        min-width: 200px;
    }

    .table th.col-phone,
    .table td.col-phone {
        width: auto;
        min-width: 150px;
    }

    .table th.col-status,
    .table td.col-status {
        width: auto;
        min-width: 120px;
    }

    .table th.col-actions,
    .table td.col-actions {
        width: auto;
        min-width: 150px;
    }

    .pagination {
        display: flex;
        justify-content: center;
    }

    /* Định dạng cho nút và thanh tìm kiếm */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .search-container {
        position: relative;
        flex-grow: 1;
        max-width: 300px;
        margin-left: 300px;
    }

    .search-container input {
        padding-right: 40px;
        /* Để chừa chỗ cho biểu tượng */
    }

    .search-container .search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            max-width: none;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .table th,
        .table td {
            font-size: 14px;
        }

        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-container {
            max-width: none;
        }
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Thanh bên -->
            <div class="sidebar">
                <h4>Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-th"></i> Bảng Điều Khiển</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#"><i class="fas fa-users"></i> Khách Hàng</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a>
                    </li>
                </ul>
            </div>
            <!-- Nội dung chính -->
            <div class="main-content">
                <h1>Quản Lý Khách Hàng</h1>
                <div class="header-actions">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal"
                        onclick="openAddModal()">+ Thêm Khách Hàng</button>
                    <div class="search-container">
                        <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm tên khách hàng"
                            onkeyup="searchCustomers()">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="customerTable">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th class="col-name">Tên</th>
                                <th class="col-email">Email</th>
                                <th class="col-phone">Điện Thoại</th>
                                <th class="col-status">Trạng Thái</th>
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
        </div>
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
                            <label for="name" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="name" required>
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
                name: 'Nguyễn Văn A',
                email: 'a@example.com',
                phone: '0123456789',
                status: 'active'
            },
            {
                id: 2,
                name: 'Trần Thị B',
                email: 'b@example.com',
                phone: '0987654321',
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
                    <td class="col-name">${customer.name}</td>
                    <td class="col-email">${customer.email}</td>
                    <td class="col-phone">${customer.phone}</td>
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
            name: 'Nguyễn Văn A',
            email: 'a@example.com',
            phone: '0123456789',
            status: 'active'
        };
        document.querySelector('#modalTitle').textContent = 'Chỉnh Sửa Khách Hàng';
        document.querySelector('#customerId').value = customer.id;
        document.querySelector('#name').value = customer.name;
        document.querySelector('#email').value = customer.email;
        document.querySelector('#phone').value = customer.phone;
        document.querySelector('#status').value = customer.status;
        new bootstrap.Modal(document.querySelector('#customerModal')).show();
    }

    function saveCustomer() {
        const id = document.querySelector('#customerId').value;
        const name = document.querySelector('#name').value;
        const email = document.querySelector('#email').value;
        const phone = document.querySelector('#phone').value;
        const status = document.querySelector('#status').value;

        if (name && email) {
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