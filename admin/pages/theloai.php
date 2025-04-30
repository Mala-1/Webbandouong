<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$permissions = $_SESSION['permissions'] ?? [];
$canWriteCategory = in_array('write', $permissions['Quản lý thể loại'] ?? []);
$canDeleteCategory = in_array('delete', $permissions['Quản lý thể loại'] ?? []);

// Lấy danh sách thể loại chưa bị xóa (is_deleted = 0)
$categories = $db->select("SELECT * FROM categories WHERE is_deleted = 0");
?>

<div class="p-3 d-flex align-items-center rounded" style="background-color: #f0f0f0; height: 80px;">
    <?php if ($canWriteCategory): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa-solid fa-plus me-1"></i> THÊM
        </button>
    <?php endif; ?>

    <!-- Thanh tìm kiếm -->
    <div class="flex-grow-1">
        <form onsubmit="return false;" class="d-flex justify-content-center mx-auto" style="max-width: 400px; width: 100%;" role="search">
            <input class="category-name form-control me-2" type="search" placeholder="Tìm kiếm tên thể loại" aria-label="Search" name="category-name">
            <button type="button" class="btn-search btn btn-sm p-0 border-0 bg-transparent">
                <i class="fas fa-search fa-lg"></i>
            </button>
        </form>
    </div>
</div>

<!-- Bảng danh sách thể loại -->
<div class="table-responsive mt-4 pe-3">
    <table class="table align-middle table-bordered">
        <thead class="table-light text-center">
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên</th>
                <?php if ($canWriteCategory || $canDeleteCategory): ?>
                    <th>Chức năng</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="category-wrap text-center align-middle">
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category['category_id'] ?></td>
                    <td><img src="/uploads/categories/<?= $category['image'] ?>" width="50" alt="<?= $category['name'] ?>"></td>
                    <td><?= htmlspecialchars($category['name']) ?></td>
                    <td>
                        <i class="fas fa-pen text-primary btn-edit-category me-3 fa-lg" style="cursor: pointer;"
                            data-id="<?= $category['category_id'] ?>"
                            data-name="<?= htmlspecialchars($category['name']) ?>"
                            data-image="<?= htmlspecialchars($category['image']) ?>"></i>
                        <i class="fas fa-trash fa-lg text-danger btn-delete-category" style="cursor: pointer;" data-id="<?= $category['category_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaCategory"></i>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="pagination-wrap"></div>

<!-- Modal thêm thể loại -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="categoryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Thêm Thể Loại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên thể loại</label>
                        <input type="text" name="category-name" class="form-control" placeholder="Nhập tên thể loại" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" name="category-image" class="form-control" accept="image/*" required>
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

<!-- Modal xóa thể loại -->
<div class="modal fade" id="modalXoaCategory" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xoá thể loại</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xoá thể loại có mã <strong id="category-id-display"></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoaCategory">Xoá</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal sửa thể loại -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="editCategoryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Sửa Thể Loại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body row g-3">
                    <input type="hidden" name="category_id" id="editCategoryId">

                    <div class="col-md-6">
                        <label class="form-label">Tên thể loại</label>
                        <input type="text" name="category-name" id="editCategoryName" class="form-control" placeholder="Nhập tên thể loại" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="d-flex align-items-center">
                            <img id="previewSelectedCategoryImage" src="" alt="Ảnh thể loại" class="border rounded" width="70" height="70">
                            <input type="file" name="category-image" id="editCategoryImage" class="form-control ms-3" accept="image/*">
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

    function loadCategories(page = 1, params = "") {
        const categoryWrap = document.querySelector('.category-wrap');
        const paginationWrap = document.querySelector('.pagination-wrap');

        fetch('ajax/load_categories.php?page=' + page + params)
            .then(res => res.text())
            .then(data => {
                const parts = data.split('SPLIT');
                categoryWrap.innerHTML = parts[0] || '';
                paginationWrap.innerHTML = parts[1] || '';
            });
    }

    // Tải danh sách thể loại khi trang được load
    loadCategories(1);

    document.addEventListener("pagination:change", function(e) {
        const {
            page,
            target
        } = e.detail;

        if (target === "categorypage") {
            loadCategories(page, currentFilterParams);
        }
    });

    // 🎯 Lắng nghe sự kiện input để tìm kiếm theo tên thể loại
    document.querySelector('input[name="category-name"]').addEventListener('input', function() {
        const name = document.querySelector('input[name="category-name"]').value.trim();

        currentFilterParams = `&search_name=${encodeURIComponent(name)}`;
        loadCategories(1, currentFilterParams);
    });

    document.getElementById("categoryForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('ajax/add_category.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                    modal.hide(); // Đóng modal

                    loadCategories(1); // Reload danh sách
                    this.reset(); // Reset form
                    alert('Thêm thể loại thành công!');
                } else {
                    alert(data.message || 'Thêm thể loại thất bại');
                }
            });
    });


    let idDangXoa = null;

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-category')) {
            e.preventDefault();
            const btn = e.target.closest('.btn-delete-category');
            idDangXoa = btn.getAttribute('data-id');

            // Gán vào modal
            document.getElementById('category-id-display').textContent = idDangXoa;
        }
    });

    document.getElementById('btnXacNhanXoaCategory').addEventListener('click', function() {
        if (!idDangXoa) return;

        fetch('ajax/delete_category.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    category_id: idDangXoa
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Xóa thành công → reload danh sách thể loại
                    loadCategories(1, currentFilterParams);
                    alert('Xóa thành công!');
                } else {
                    alert('Xóa thất bại: ' + data.message);
                }
                // Đóng modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaCategory'));
                modal.hide();
            });
    });


    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.btn-edit-category');
        if (editBtn) {
            const categoryId = editBtn.dataset.id;
            const name = editBtn.dataset.name;
            const image = editBtn.dataset.image;

            // Gán dữ liệu vào modal
            document.getElementById('editCategoryId').value = categoryId;
            document.getElementById('editCategoryName').value = name;
            document.getElementById('previewSelectedCategoryImage').src = "../../assets/images/theloai/" + image; // Hiển thị ảnh hiện tại

            const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        }
    });

    // 🎯 Thay đổi ảnh ngay khi chọn file mới
    document.getElementById('editCategoryImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewSelectedCategoryImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById("editCategoryForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('ajax/update_category.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
                    modal.hide();
                    loadCategories(1, currentFilterParams);
                    alert('Cập nhật thể loại thành công!');
                } else {
                    alert(data.message || 'Lỗi cập nhật');
                }
            });
    });
</script>