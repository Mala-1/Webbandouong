<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 37px !important;
        line-height: 35px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 35px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    table img {
        border-radius: 6px;
        object-fit: cover;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .ellipsis {
        white-space: nowrap;
        /* Không xuống dòng */
        overflow: hidden;
        /* Ẩn phần tràn ra */
        text-overflow: ellipsis;
        /* Hiện dấu "..." */
    }

    .border-dashed-long {
        border: 1px dashed transparent;
        border-image: repeating-linear-gradient(45deg, black 0, black 30px, transparent 10px, transparent 35px);
        border-image-slice: 1;
    }

    #drop-zone {
        cursor: pointer;
        border: 2px dashed #ccc;
        transition: border-color 0.3s;
    }

    #drop-zone.dragover {
        border-color: #007bff;
        background-color: #f0f8ff;
    }

    #preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
    }
</style>

<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$productPermissions = $_SESSION['permissions']['Quản lý sản phẩm'] ?? [];
$canWrite = in_array('write', $productPermissions);
$canDelete = in_array('delete', $productPermissions);

$categories = $db->select('SELECT * FROM categories WHERE is_deleted = 0');

$brands = $db->select('SELECT * FROM brand WHERE is_deleted = 0');

?>
<div>
    <div class="p-3 d-flex align-items-center rounded" style="background-color: #f0f0f0; height: 80px;">
        <?php if (in_array('write', $productPermissions)): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalThemSanPham">
                <i class="fa-solid fa-plus me-1"></i> THÊM
            </button>
        <?php endif; ?>


        <!-- Thanh tìm kiếm -->
        <div class="flex-grow-1">
            <form class="d-flex justify-content-center mx-auto" style="max-width: 400px; width: 100%;" role="search" id="form-search-name">
                <input class="product-name form-control me-2" type="search" placeholder="Tìm kiếm tên sản phẩm" aria-label="Search">
                <button type="button" class="btn-search btn btn-sm p-0 border-0 bg-transparent">
                    <i class="fas fa-search fa-lg"></i>
                </button>
            </form>

        </div>
    </div>
    <!-- Tìm kiếm nâng cao -->
    <form method="GET" action=""
        class="form-search d-flex gap-2 align-items-center container mt-3 flex-wrap justify-content-center">
        <input type="number" class="min-price form-control w-auto" style="width: 120px;" name="price_min" placeholder="Giá từ">
        <input type="number" class="max-price form-control w-auto" style="width: 120px;" name="price_max" placeholder="Giá đến">

        <select class="categorySearch form-select w-auto" style="width: 180px;" name="category">
            <option value="">Tất cả</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
            <?php endforeach; ?>
        </select>

        <select class="brandSearch form-select w-auto" style="width: 180px; height: 100px;">
            <option value=""></option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['brand_id'] ?>"><?= $brand['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="table-responsive mt-4 pe-3">
        <table class="table align-middle table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th scope="col">Mã SP</th>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Giá bán</th>
                    <th scope="col">Thể loại</th>
                    <th scope="col">Thương hiệu</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Đơn vị đóng gói</th>
                    <th scope="col">Nơi sản xuất</th>
                    <th scope="col">Mô tả</th>
                    <?php if ($canWrite || $canDelete): ?>
                        <th scope="col" style="min-width: 120px;">Chức năng</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="product-wrap text-center align-middle">

                <!-- Thêm dòng khác tương tự -->
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">

    </div>
</div>
<!-- Modal thêm sản phẩm -->
<div class="modal fade" id="modalThemSanPham" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" method="POST" action="add_product.php" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Thêm sản phẩm mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Size</label>
                        <input type="text" name="size" class="form-control" required>
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <label>Loại sản phẩm</label>
                        <select class="category form-select" name="category">
                            <option value=""></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <label>Thương hiệu</label>
                        <select class="brand form-select" name="brand">
                            <option value=""></option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['brand_id'] ?>"><?= $brand['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-12 p-2">
                        <!-- Kéo thả ẩnh -->
                        <div id="drop-zone" class="border border-secondary border-dashed text-center p-4 rounded">
                            <div id="drop-message">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-muted"></i>
                                <p class="text-muted m-0">Kéo thả ảnh vào đây</p>
                            </div>

                            <!-- Nơi hiển thị tên file + nút xoá -->
                            <ul id="fileList" class="list-unstyled mt-3"></ul>

                            <div id="imagePreviewContainer" class="mt-3">
                                <img id="imagePreview" src="" alt="Xem ảnh" class="img-fluid rounded shadow"
                                    style="max-height: 300px; display: none;">
                            </div>

                            <!-- input ẩn, chỉ dùng để nhận file -->
                            <input type="file" id="fileInput" name="images_hidden[]" multiple hidden accept="image/*">
                        </div>
                        <input type="file" id="fileInputOutside" class="mt-2" name="images_outside[]" multiple accept="image/*">
                    </div>

                    <div class="col-6">
                        <label>Đơn vị đóng gói</label>
                        <input type="text" class="form-control" name="unit">
                    </div>

                    <div class="col-6">
                        <label>Nơi sản xuất</label>
                        <input type="text" name="origin" class="form-control">
                    </div>

                    <div class="col-12">
                        <label>Kiểu đóng gói</label>
                        <table class="table align-middle table-bordered">
                            <thead class="table-light text-center">
                                <tr>
                                    <th scope="col">Tên kiểu đóng gói</th>
                                    <th scope="col">Số lượng trên đơn vị đóng gói</th>
                                    <th scope="col">Ảnh</th>
                                </tr>
                            </thead>
                            <tbody id="packagingBody">
                                <!-- khi click thêm thì thêm dòng mới ở đây -->
                                <tr id="addRowTrigger">
                                    <td colspan="3">
                                        <button class="btn btn-success" id="btnAddRow" type="button">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            Thêm
                                        </button>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Xác nhận thêm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal xác nhận xoá sản phẩm -->
<div class="modal fade" id="modalXoaSanPham" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xoá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xoá sản phẩm có mã <strong id="product-id-display"></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button type="button" class="btn btn-danger" id="btnXacNhanXoa">Xoá</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa sản phẩm -->
<div class="modal fade" id="modalSuaSanPham" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content" id="formSuaSanPham" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Sửa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="product_id" id="sua_product_id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Tên sản phẩm</label>
                        <input type="text" name="name" id="sua_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Size</label>
                        <input type="text" name="size" id="sua_size" class="form-control" required>
                    </div>

                    <div class="col-md-6 d-flex flex-column">
                        <label>Loại sản phẩm</label>
                        <select class="category form-select" name="category" id="sua_category">
                            <!-- Options sẽ được render lại -->
                        </select>
                    </div>

                    <div class="col-md-6 d-flex flex-column">
                        <label>Thương hiệu</label>
                        <select class="brand form-select" name="brand" id="sua_brand">
                            <!-- Options sẽ được render lại -->
                        </select>
                    </div>

                    <div class="col-12">
                        <label>Mô tả</label>
                        <textarea name="description" id="sua_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-12 p-2">
                        <div id="drop-zone-sua" class="border border-secondary border-dashed text-center p-4 rounded">
                            <div id="drop-message-sua">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-muted"></i>
                                <p class="text-muted m-0">Kéo thả ảnh vào đây</p>
                            </div>
                            <ul id="fileListSua" class="list-unstyled mt-3"></ul>
                            <div id="imagePreviewContainerSua" class="mt-3">
                                <img id="imagePreviewSua" src="" alt="Xem ảnh" class="img-fluid rounded shadow"
                                    style="max-height: 300px; display: none;">
                            </div>
                            <input type="file" id="fileInputSua" name="images_hidden[]" multiple hidden accept="image/*">
                        </div>
                        <input type="file" id="fileInputOutsideSua" class="mt-2" name="images_outside[]" multiple accept="image/*">
                    </div>

                    <div class="col-6">
                        <label>Đơn vị đóng gói</label>
                        <input type="text" class="form-control" name="unit" id="sua_unit">
                    </div>

                    <div class="col-6">
                        <label>Nơi sản xuất</label>
                        <input type="text" name="origin" id="sua_origin" class="form-control">
                    </div>

                    <div class="col-12">
                        <label>Kiểu đóng gói</label>
                        <table class="table align-middle table-bordered">
                            <thead class="table-light text-center">
                                <tr>
                                    <th scope="col">Tên kiểu đóng gói</th>
                                    <th scope="col">Số lượng trên đơn vị đóng gói</th>
                                    <th scope="col">Ảnh</th>
                                    <th scope="col">Xóa</th> <!-- ✅ Cột mới -->
                                </tr>
                            </thead>
                            <tbody id="packagingBodySua">
                                <!-- sẽ render động các dòng ở đây -->
                                <tr id="addRowTriggerSua">
                                    <td colspan="4" class="text-center">
                                        <button class="btn btn-success" id="btnAddRowSua" type="button">
                                            <i class="fa-solid fa-circle-plus"></i>
                                            Thêm
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </form>
    </div>
</div>



<!-- Modal xem chi tiết sản phẩm -->
<div class="modal fade" id="modalXemSanPham" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Tên sản phẩm</label>
                        <input type="text" id="xem_name" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Size</label>
                        <input type="text" id="xem_size" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Loại sản phẩm</label>
                        <select id="xem_category" class="form-select" disabled></select>
                    </div>

                    <div class="col-md-6">
                        <label>Thương hiệu</label>
                        <select id="xem_brand" class="form-select" disabled></select>
                    </div>

                    <div class="col-12">
                        <label>Mô tả</label>
                        <textarea id="xem_description" class="form-control" rows="3" readonly></textarea>
                    </div>

                    <div class="col-12">
                        <label>Ảnh</label>
                        <div id="xem_images" class="d-flex gap-2 flex-wrap"></div>
                    </div>

                    <div class="col-6">
                        <label>Đơn vị đóng gói</label>
                        <input type="text" id="xem_unit" class="form-control" readonly>
                    </div>

                    <div class="col-6">
                        <label>Nơi sản xuất</label>
                        <input type="text" id="xem_origin" class="form-control" readonly>
                    </div>

                    <div class="col-12">
                        <label>Kiểu đóng gói</label>
                        <table class="table table-bordered">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Tên kiểu</th>
                                    <th>Số lượng/đơn vị</th>
                                    <th>Ảnh</th>
                                </tr>
                            </thead>
                            <tbody id="xem_packaging_body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const categories = <?= json_encode($categories) ?>;
    const brands = <?= json_encode($brands) ?>;

    // Kích hoạt tìm kiếm trong select
    $(document).ready(function() {
        $('.categorySearch').select2({
            placeholder: "Chọn loại sản phẩm",
            allowClear: true
        });
        // Kích hoạt Select2 cho brand (thương hiệu)
        $('.brandSearch').select2({
            placeholder: "Chọn thương hiệu",
            allowClear: true
        });

        // Với select trong modal
        $('#modalThemSanPham .category').select2({
            placeholder: "Chọn loại sản phẩm",
            allowClear: true,
            dropdownParent: $('#modalThemSanPham') // đảm bảo hiển thị đúng trong modal
        });

        $('#modalThemSanPham .brand').select2({
            placeholder: "Chọn thương hiệu",
            allowClear: true,
            dropdownParent: $('#modalThemSanPham')
        });

        $('#modalSuaSanPham .category').select2({
            placeholder: "Chọn loại sản phẩm",
            allowClear: true,
            dropdownParent: $('#modalSuaSanPham')
        });
        $('#modalSuaSanPham .brand').select2({
            placeholder: "Chọn thương hiệu",
            allowClear: true,
            dropdownParent: $('#modalSuaSanPham')
        });
    });


    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const dropMessage = document.getElementById('drop-message');

    let currentFiles = []; // mảng chứa file đang được chọn

    // Hiệu ứng drag
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    // Kéo thả file
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // Ẩn hiển icon
    function updateDropMessage() {
        dropMessage.style.display = currentFiles.length > 0 ? 'none' : 'block';
    }

    // Xử lý file
    function handleFiles(files) {
        Array.from(files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            currentFiles.push(file);

            const reader = new FileReader();
            reader.onload = function(e) {
                const li = document.createElement('li');
                li.innerHTML = `
                    <a href="#" class="file-link text-primary text-decoration-underline" data-src="${e.target.result}">
                    ${file.name}
                    </a>
                    <button type="button" class="btn btn-close" onclick="removeFile(${currentFiles.length - 1})"></button>
                `;
                fileList.appendChild(li);
            };

            reader.readAsDataURL(file); // Quan trọng!
        });

        updateDropMessage();
    }


    // Xoá file theo index
    function removeFile(index) {
        currentFiles.splice(index, 1);
        renderFileList();
        updateDropMessage();
    }

    // Cập nhật danh sách hiển thị lại
    function renderFileList() {
        fileList.innerHTML = '';
        currentFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const li = document.createElement('li');
                li.innerHTML = `
                <a href="#" class="file-link text-primary text-decoration-underline me-1" data-src="${e.target.result}">
                    ${file.name}
                </a>
                <button type="button" class="btn btn-close" onclick="removeFile(${idx})"></button>
            `;
                fileList.appendChild(li);
            };
            reader.readAsDataURL(file);
        });
    }


    // Bắt sự kiện khi chọn ảnh bằng input ngoài
    document.getElementById('fileInputOutside').addEventListener('change', function() {
        handleFiles(this.files);
    });


    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('file-preview')) {
            e.preventDefault();

            const src = e.target.getAttribute('data-src');
            const img = document.createElement('img');
            img.src = src;
            img.alt = "Ảnh xem nhanh";
            img.style.maxHeight = '300px';
            img.style.marginTop = '10px';
            img.className = 'img-fluid rounded shadow';

            // Xoá ảnh cũ nếu có
            const oldPreview = document.getElementById('quick-preview');
            if (oldPreview) oldPreview.remove();

            // Thêm ảnh mới dưới danh sách
            img.id = 'quick-preview';
            document.getElementById('fileList').after(img);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('file-link')) {
            e.preventDefault();
            const imgSrc = e.target.getAttribute('data-src');
            const previewImg = document.getElementById('imagePreview');
            previewImg.src = imgSrc;
            previewImg.style.display = 'block';
        }
    });

    document.getElementById('packagingBody').addEventListener('click', function(e) {
        if (e.target && e.target.closest('#btnAddRow')) {
            // ✅ Click đúng vào nút "Thêm"

            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="packaging_name[]" class="form-control" placeholder="Nhập kiểu đóng gói"></td>
                <td><input type="number" name="unit_quantity[]" class="form-control" placeholder="Số lượng"></td>
                <td><input type="file" name="packaging_image[]" class="form-control" accept="image/*"></td>
            `;

            const tbody = document.getElementById('packagingBody');
            const addRowTrigger = document.getElementById('addRowTrigger');
            tbody.insertBefore(newRow, addRowTrigger);
        }
    });

    let currentFilterParams = "";

    function loadProducts(page = 1, params = "") {
        const productWrap = document.querySelector('.product-wrap');
        const paginationWrap = document.querySelector('.pagination-wrap');
        fetch('ajax/load_products.php?page=' + page + params)
            .then(res => res.text())
            .then(data => {
                const parts = data.split('SPLIT');
                productWrap.innerHTML = parts[0] || '';
                paginationWrap.innerHTML = parts[1] || '';
            })
    }
    loadProducts(1);

    document.addEventListener("pagination:change", function(e) {
        const {
            page,
            target
        } = e.detail;

        if (target === "pageproduct") {
            loadProducts(page, currentFilterParams);
        }

        // Add more targets as needed
    });

    // lọc

    const searchForm = document.getElementById('form-search-name');
    const searchInput = searchForm.querySelector('.product-name');
    const searchButton = searchForm.querySelector('.btn-search');

    // Hàm xử lý tìm kiếm
    function handleProductNameSearch() {
        const name = searchInput.value.trim();
        const params = new URLSearchParams(currentFilterParams);

        if (name) {
            params.set('product_name', name);
        } else {
            params.delete('product_name');
        }

        currentFilterParams = '&' + params.toString();
        loadProducts(1, currentFilterParams);
    }

    // Click nút tìm
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        handleProductNameSearch();
    });

    // Nhấn Enter trong input
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleProductNameSearch();
        }
    });


    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            const context = this; // giữ context để dùng đúng `this`
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }

    const form = document.querySelector('.form-search');
    const inputs = form.querySelectorAll('input');

    inputs.forEach(input => {
        // Gọi handleFilterChange sau khi dừng gõ 500ms
        input.addEventListener('input', debounce(handleFilterChange, 300));

        // Gọi ngay khi nhấn Enter
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault(); // tránh reload form nếu có
                handleFilterChange(); // gọi ngay, không debounce
            }
        });
        // Trường hợp đặc biệt như Ctrl+A rồi Delete → dùng keyup để bắt kịp
        input.addEventListener('keyup', (e) => {
            if (e.key === 'Delete' || e.key === 'Backspace') {
                // Đợi 1 tick sau khi DOM cập nhật xong
                setTimeout(() => {
                    handleFilterChange();
                }, 300);
            }
        });
    });

    // 🎯 Với select2: lắng nghe sự kiện change qua class cụ thể
    $('.categorySearch, .brandSearch').on('change', function() {
        handleFilterChange();
    });

    function handleFilterChange() {
        const name = document.querySelector('.product-name').value.trim();
        const category = document.querySelector('.categorySearch').value;
        const brand = document.querySelector('.brandSearch').value;
        const priceMin = document.querySelector('.min-price').value.trim();
        const priceMax = document.querySelector('.max-price').value.trim();

        currentFilterParams = "";

        if (name) currentFilterParams += '&product_name=' + encodeURIComponent(name);
        if (category) currentFilterParams += '&category_id=' + encodeURIComponent(category);
        if (brand) currentFilterParams += '&brand_id=' + encodeURIComponent(brand);
        if (priceMin) currentFilterParams += '&price_min=' + encodeURIComponent(priceMin);
        if (priceMax) currentFilterParams += '&price_max=' + encodeURIComponent(priceMax);

        loadProducts(1, currentFilterParams);
    }

    let idDangXoa = null;

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-product')) {
            e.preventDefault();
            const btn = e.target.closest('.btn-delete-product');
            idDangXoa = btn.getAttribute('data-id');

            // Gán vào modal
            document.getElementById('product-id-display').textContent = idDangXoa;
        }
    });

    document.getElementById('btnXacNhanXoa').addEventListener('click', function() {
        if (!idDangXoa) return;

        fetch('ajax/delete_product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: idDangXoa
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Xoá thành công → reload danh sách
                    loadProducts(1, currentFilterParams);
                } else {
                    alert('Xoá thất bại: ' + data.message);
                }
                // Đóng modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaSanPham'));
                modal.hide();
            });
    });

    document.querySelector('#modalThemSanPham form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const dataTransfer = new DataTransfer();
        currentFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;

        const formData = new FormData(form);
        fetch('ajax/add_product.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                console.log(data)
                if (data.debug) {
                    alert("Debug ảnh: " + data.filename);
                    return;
                }
                if (data.success) {
                    alert('Thêm sản phẩm thành công!');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalThemSanPham'));
                    modal.hide();

                    form.reset(); // Reset các input chuẩn

                    // ✅ Reset vùng kéo thả ảnh
                    currentFiles = [];
                    fileInput.value = ""; // clear input file hidden
                    fileList.innerHTML = "";
                    imagePreviewContainer.style.display = "none"; // nếu bạn có hiển thị ảnh

                    // ✅ Reset vùng input ngoài
                    document.getElementById('fileInputOutside').value = "";

                    // ✅ Reset drop message
                    updateDropMessage();

                    // ✅ Reset packaging table nếu có
                    const packagingBody = document.getElementById('packagingBody');
                    packagingBody.innerHTML = `
                        <tr id="addRowTrigger">
                            <td colspan="3">
                                <button class="btn btn-success" id="btnAddRow" type="button">
                                    <i class="fa-solid fa-circle-plus"></i> Thêm
                                </button>
                            </td>
                        </tr>
                    `;


                    // ✅ Reset Select2 hoặc các plugin select khác nếu có
                    $('.category').val('').trigger('change');
                    $('.brand').val('').trigger('change');

                    loadProducts(1);
                } else {
                    alert('Thêm thất bại: ' + data.message);
                }
            })
            .catch(err => {
                alert('Lỗi khi thêm: ' + err.message);
            });
    });

    // Vùng xử lý kéo thả ảnh cho modal sửa
    const dropZoneSua = document.getElementById('drop-zone-sua');
    const fileInputSua = document.getElementById('fileInputSua');
    const fileListSua = document.getElementById('fileListSua');
    const dropMessageSua = document.getElementById('drop-message-sua');
    const imagePreviewSua = document.getElementById('imagePreviewSua');
    const imagePreviewContainerSua = document.getElementById('imagePreviewContainerSua');
    let currentFilesSua = [];

    // Kéo thả ảnh sửa
    dropZoneSua.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZoneSua.classList.add('dragover');
    });
    dropZoneSua.addEventListener('dragleave', () => {
        dropZoneSua.classList.remove('dragover');
    });
    dropZoneSua.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZoneSua.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFilesSua(files);
    });

    function handleFilesSua(files) {
        currentFilesSua = [];
        fileListSua.innerHTML = '';
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            currentFilesSua.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewSua.src = e.target.result;
                imagePreviewSua.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
        dropMessageSua.style.display = 'none';
    }

    function removeFileSua(index) {
        // Nếu bạn muốn xoá trong `currentFilesSua` thì thêm xử lý tại đây
        // Với ảnh đã có sẵn thì chỉ cần xoá khỏi giao diện thôi
        const listItems = fileListSua.querySelectorAll('li');
        if (listItems[index]) listItems[index].remove();
    }


    // Click nút chi tiết sản phẩm (icon sửa)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-sua-sanpham');
        if (!btn) return;

        const categoryId = btn.dataset.category;
        const brandId = btn.dataset.brand;

        // --- Gán danh sách category ---
        const categorySelect = document.getElementById('sua_category');
        categorySelect.innerHTML = '<option value=""></option>';
        categories.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.category_id;
            opt.textContent = c.name;
            categorySelect.appendChild(opt);
        });
        // Gán giá trị + trigger Select2
        $('#sua_category').val(categoryId).trigger('change');

        // --- Gán danh sách brand ---
        const brandSelect = document.getElementById('sua_brand');
        brandSelect.innerHTML = '<option value=""></option>';
        brands.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.brand_id;
            opt.textContent = b.name;
            brandSelect.appendChild(opt);
        });
        // Gán giá trị + trigger Select2
        $('#sua_brand').val(brandId).trigger('change');


        let packaging = [];
        try {
            packaging = JSON.parse(btn.dataset.packaging || "[]");
            console.log("packaging decoded:", packaging);
        } catch (e) {
            console.error("Không thể parse packaging:", e);
        }
        const tbody = document.getElementById('packagingBodySua');
        tbody.innerHTML = ''; // Clear cũ
        // console.log("raw data-packaging = ", btn.dataset.packaging);
        console.log("packaging decoded:", packaging);


        packaging.forEach(option => {
            const raw = option.unit_quantity + '';
            const number = parseInt(raw);
            const unit = raw.replace(/\d+/g, '').trim();
            if (number !== 1) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <input type="hidden" name="packaging_option_id[]" value="${option.packaging_option_id}">
            <td><input type="text" name="packaging_name[]" class="form-control" value="${option.packaging_type}"></td>
            <td><input type="number" name="unit_quantity[]" class="form-control" value="${number}"></td>
            <td class="d-flex align-items-center gap-2">
                ${option.image ? `<img src="/assets/images/SanPham/${option.image}" style="width: 50px;">` : ''}
                <input type="file" name="packaging_image[]" class="form-control">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btnRemoveRow">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;
                tbody.appendChild(tr);
            }
        });

        const addRowTrigger = document.createElement('tr');
        addRowTrigger.id = 'addRowTriggerSua';
        addRowTrigger.innerHTML = `
    <td colspan="4" class="text-center">
        <button class="btn btn-success" id="btnAddRowSua" type="button">
            <i class="fa-solid fa-circle-plus"></i> Thêm
        </button>
    </td>`;
        tbody.appendChild(addRowTrigger);


        // Gán các input còn lại
        document.getElementById('sua_product_id').value = btn.dataset.id;
        document.getElementById('sua_name').value = btn.dataset.name;
        document.getElementById('sua_size').value = btn.dataset.size;
        document.getElementById('sua_origin').value = btn.dataset.origin;
        document.getElementById('sua_description').value = btn.dataset.description;
        document.getElementById('sua_unit').value = btn.dataset.unit;

        // Reset ảnh cũ
        currentFilesSua = [];
        fileInputSua.value = "";
        fileListSua.innerHTML = "";
        imagePreviewSua.style.display = 'none';
        dropMessageSua.style.display = 'block';

        const images = JSON.parse(btn.dataset.images || '[]');
        if (images.length > 0) {
            images.forEach((imgName, index) => {
                const li = document.createElement('li');
                li.innerHTML = `
                <input type="hidden" name="old_images[]" value="${imgName}">
                <a href="#" class="file-link text-primary text-decoration-underline" data-src="/assets/images/SanPham/${imgName}">
                    ${imgName}
                </a>
                <button type="button" class="btn btn-close" onclick="removeFileSua(${index})"></button>
            `;
                fileListSua.appendChild(li);
            });
        }

        // Mở modal sửa
        const modal = new bootstrap.Modal(document.getElementById('modalSuaSanPham'));
        modal.show();
    });


    // Gán sự kiện thêm dòng đóng gói (event delegation)
    document.getElementById('packagingBodySua').addEventListener('click', function(e) {
        if (e.target.closest('#btnAddRowSua')) {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="packaging_name[]" class="form-control"></td>
                <td><input type="text" name="unit_quantity[]" class="form-control"></td>
                <td><input type="file" name="packaging_image[]" class="form-control" accept="image/*"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btnRemoveRow">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;
            this.insertBefore(newRow, document.getElementById('addRowTriggerSua'));
        }
    });

    document.getElementById('packagingBodySua').addEventListener('click', function(e) {
        if (e.target.closest('.btnRemoveRow')) {
            const row = e.target.closest('tr');
            if (row && !row.id.includes("addRowTriggerSua")) {
                row.remove();
            }
        }
    });


    // Submit form sửa
    document.getElementById('formSuaSanPham').addEventListener('submit', function(e) {
        e.preventDefault();

        // Gán lại file kéo thả vào input ẩn
        const dt = new DataTransfer();
        currentFilesSua.forEach(file => dt.items.add(file));
        fileInputSua.files = dt.files;

        const formData = new FormData(this);

        fetch('ajax/update_product.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Cập nhật thành công!');
                    bootstrap.Modal.getInstance(document.getElementById('modalSuaSanPham')).hide();
                    this.reset();
                    currentFilesSua = [];
                    loadProducts(1); // Cập nhật lại danh sách sản phẩm
                } else {
                    alert(data.message);
                    console.log(data.message);
                }
            })
            .catch(err => {
                alert('Lỗi khi gửi form: ' + err.message);
            });
    });

    document.getElementById('fileInputOutsideSua').addEventListener('change', function() {
        handleFilesSua(this.files);
    });

    function handleFilesSua(files) {
        Array.from(files).forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            currentFilesSua.push(file);

            const reader = new FileReader();
            reader.onload = function(e) {
                const li = document.createElement('li');
                li.innerHTML = `
                <a href="#" class="file-link text-primary text-decoration-underline" data-src="${e.target.result}">
                    ${file.name}
                </a>
                <button type="button" class="btn btn-close" onclick="removeFileSua(${currentFilesSua.length - 1})"></button>
            `;
                fileListSua.appendChild(li);

                // Chỉ hiển thị preview nếu chưa có ảnh hiển thị
                if (imagePreviewSua.style.display === 'none') {
                    imagePreviewSua.src = e.target.result;
                    // imagePreviewSua.style.display = 'block';
                    // dropMessageSua.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        });
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-xem-sanpham');
        if (!btn) return;

        document.getElementById('xem_name').value = btn.dataset.name;
        document.getElementById('xem_size').value = btn.dataset.size;
        document.getElementById('xem_description').value = btn.dataset.description;
        document.getElementById('xem_unit').value = btn.dataset.unit;
        document.getElementById('xem_origin').value = btn.dataset.origin;

        // Gán select category/brand
        const xemCategory = document.getElementById('xem_category');
        xemCategory.innerHTML = categories.map(c =>
            `<option value="${c.category_id}" ${c.category_id == btn.dataset.category ? 'selected' : ''}>${c.name}</option>`
        ).join('');

        const xemBrand = document.getElementById('xem_brand');
        xemBrand.innerHTML = brands.map(b =>
            `<option value="${b.brand_id}" ${b.brand_id == btn.dataset.brand ? 'selected' : ''}>${b.name}</option>`
        ).join('');

        // Gán ảnh sản phẩm
        const imageWrap = document.getElementById('xem_images');
        const images = JSON.parse(btn.dataset.images || '[]');
        imageWrap.innerHTML = images.map(img =>
            `<img src="/assets/images/SanPham/${img}" class="rounded border" width="80" height="80">`
        ).join('');

        // Gán bảng đóng gói
        const packagingBody = document.getElementById('xem_packaging_body');
        let packaging = [];
        try {
            packaging = JSON.parse(btn.dataset.packaging || "[]");
        } catch (err) {}
        packagingBody.innerHTML = packaging.map(p =>
            `<tr>
      <td>${p.packaging_type}</td>
      <td>${p.unit_quantity}</td>
      <td>${p.image ? `<img src="/assets/images/SanPham/${p.image}" width="50">` : ''}</td>
    </tr>`
        ).join('');

        new bootstrap.Modal(document.getElementById('modalXemSanPham')).show();
    });
</script>