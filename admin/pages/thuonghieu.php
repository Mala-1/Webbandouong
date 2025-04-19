<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$permissions = $_SESSION['permissions'] ?? [];

$canReadBrand = in_array('read', $permissions['Quản lý thương hiệu'] ?? []);
$canWriteBrand = in_array('write', $permissions['Quản lý thương hiệu'] ?? []);
$canDeleteBrand = in_array('delete', $permissions['Quản lý thương hiệu'] ?? []);

// Lấy danh sách thương hiệu chưa bị xóa (is_deleted = 0)
$brands = $db->select("SELECT * FROM brand WHERE is_deleted = 0");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thương hiệu</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="p-3 d-flex align-items-center rounded" style="background-color: #f0f0f0; height: 80px;">
        <?php if ($canWriteBrand): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                <i class="fa-solid fa-plus me-1"></i> THÊM
            </button>
        <?php endif; ?>

        <!-- Thanh tìm kiếm -->
        <div class="flex-grow-1">
            <form class="d-flex justify-content-center mx-auto" style="max-width: 400px; width: 100%;" role="search">
                <input class="brand-name form-control me-2" type="search" placeholder="Tìm kiếm tên thương hiệu" aria-label="Search" name="brand-name">
                <button type="button" class="btn-search btn btn-sm p-0 border-0 bg-transparent">
                    <i class="fas fa-search fa-lg"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách thương hiệu -->
    <div class="table-responsive mt-4 pe-3">
        <table class="table align-middle table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên</th>
                    <?php if ( $canWriteBrand || $canDeleteBrand): ?>
                        <th>Chức năng</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="brand-wrap text-center align-middle">

            </tbody>
        </table>
    </div>
    <div class="pagination-wrap"></div>


    <!-- Modal thêm thương hiệu -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="brandForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBrandModalLabel">Thêm Thương Hiệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên thương hiệu</label>
                            <input type="text" name="brand-name" class="form-control" placeholder="Nhập tên thương hiệu" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hình ảnh</label>
                            <input type="file" name="brand-image" class="form-control" accept="image/*" required>
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

    <!-- Modal xác nhận xoá thương hiệu -->
    <div class="modal fade" id="modalXoaBrand" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xoá thương hiệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xoá thương hiệu có mã <strong id="brand-id-display"></strong> không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="button" class="btn btn-danger" id="btnXacNhanXoaBrand">Xoá</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Thương Hiệu -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editBrandForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBrandModalLabel">Sửa Thương Hiệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <input type="hidden" name="brand_id" id="editBrandId">

                        <div class="col-md-6">
                            <label class="form-label">Tên thương hiệu</label>
                            <input type="text" name="brand-name" id="editBrandName" class="form-control" placeholder="Nhập tên thương hiệu" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hình ảnh hiện tại</label>
                            <div class="d-flex align-items-center">
                                <img id="previewSelectedImage" src="" alt="Ảnh thương hiệu" class="border rounded" width="70" height="70">
                                <input type="file" name="brand-image" id="editBrandImage" class="form-control ms-3" accept="image/*">
                            </div>
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

    <script>
        let currentFilterParams = '';

        function loadBrands(page = 1, params = "") {
            const brandWrap = document.querySelector('.brand-wrap');
            const paginationWrap = document.querySelector('.pagination-wrap');

            fetch('ajax/load_brand.php?page=' + page + params)
                .then(res => res.text())
                .then(data => {
                    const parts = data.split('SPLIT');
                    brandWrap.innerHTML = parts[0] || '';
                    paginationWrap.innerHTML = parts[1] || '';
                });
        }

        // Tải danh sách thương hiệu khi trang được load
        loadBrands(1);

        document.addEventListener("pagination:change", function(e) {
            const {
                page,
                target
            } = e.detail;

            if (target === "brandpage") {
                loadBrands(page, currentFilterParams);
            }
        });
        // 🎯 Lắng nghe sự kiện input để tìm kiếm theo tên thương hiệu
        document.querySelector('input[name="brand-name"]').addEventListener('input', function() {
            const name = document.querySelector('input[name="brand-name"]').value.trim();

            currentFilterParams = `&search_name=${encodeURIComponent(name)}`;
            loadBrands(1, currentFilterParams);
        });

        document.getElementById("brandForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('ajax/add_brand.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addBrandModal'));
                        modal.hide(); // Đóng modal

                        loadBrands(1); // Reload danh sách
                        this.reset(); // Reset form
                        alert('Thêm thương hiệu thành công!');
                    } else {
                        alert(data.message || 'Thêm thương hiệu thất bại');
                    }
                });
        });

        let idDangXoa = null;

        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-delete-brand')) {
                e.preventDefault();
                const btn = e.target.closest('.btn-delete-brand');
                idDangXoa = btn.getAttribute('data-id');

                // Gán vào modal
                document.getElementById('brand-id-display').textContent = idDangXoa;
            }
        });

        document.getElementById('btnXacNhanXoaBrand').addEventListener('click', function() {
            if (!idDangXoa) return;

            fetch('ajax/delete_brand.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        brand_id: idDangXoa
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Xoá thành công → reload danh sách thương hiệu
                        loadBrands(1, currentFilterParams);
                        alert('Xóa thành công!');
                    } else {
                        alert('Xoá thất bại: ' + data.message);
                    }
                    // Đóng modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaBrand'));
                    modal.hide();
                });
        });


        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.btn-edit-brand');
            if (editBtn) {
                const brandId = editBtn.dataset.id;
                const name = editBtn.dataset.name;
                const image = editBtn.dataset.image;

                // Gán dữ liệu vào modal
                document.getElementById('editBrandId').value = brandId;
                document.getElementById('editBrandName').value = name;
                document.getElementById('previewSelectedImage').src = "../../assets/images/Brand/" + image; // Hiển thị ảnh hiện tại

                const editModal = new bootstrap.Modal(document.getElementById('editBrandModal'));
                editModal.show();
            }
        });

        // 🎯 Thay đổi ảnh ngay khi chọn file mới
        document.getElementById('editBrandImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewSelectedImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById("editBrandForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('ajax/update_brand.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editBrandModal'));
                        modal.hide();
                        loadBrands(1, currentFilterParams);
                        alert('Cập nhật thương hiệu thành công!');
                    } else {
                        alert(data.message || 'Lỗi cập nhật');
                    }
                });
        });
    </script>
</body>

</html>