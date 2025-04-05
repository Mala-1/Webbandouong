<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Document</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <style>
        .container,
        .wrap {
            background-color: #e2e2e2;
        }

        .ellipsis-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Giới hạn tối đa là 3 dòng */
            -webkit-box-orient: vertical;
            /* Đảm bảo rằng các dòng được xếp theo chiều dọc */
            overflow: hidden;
            /* Ẩn phần vượt quá */
            text-overflow: ellipsis;
            /* Hiện dấu "..." */
            white-space: normal;
            /* Cho phép xuống dòng khi cần thiết */
            word-wrap: break-word;
            word-break: break-word;

        }

        .product-item {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .btn-buy {
            background-color: #d3d2d2;
        }

        .btn-buy:hover {
            background-color: #303030;
            color: white !important;
        }

        .pag {
            border: none;
            background: transparent;
            width: auto;
            text-align: center;
            outline: none;
            /* bỏ viền khi focus */
            font-size: inherit;
            font-family: inherit;
            padding: 0;
            width: 25px;
        }

        .btn-prev,
        .btn-next {
            height: 80%;
        }

        .category {
            min-width: 70px;
            width: 70px;
            cursor: pointer;
        }

        .category:hover {
            background-color: #d3d2d2;
        }

        .category.active {
            border: #303030 solid 1px;
            background-color: #d3d2d2;
            border-radius: 3px;
        }

        .category img {
            width: 100%;
            object-fit: contain;
            margin: auto;
        }

        .btn-scroll {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            border: none;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s;
        }

        .scroll-left {
            bottom: -8px;
        }

        .scroll-right {
            bottom: -8px;
            right: 0;
        }

        .brand-option.active {
            border: 2px solid #198754;
            box-shadow: 0 0 5px #198754;
            border-radius: 5px;
        }

        .filter-option {
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s;
        }

        .filter-option:hover {
            background-color: #f0f0f0;
        }

        .filter-option.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <?php
        require_once '../includes/DBConnect.php';

        $db = DBConnect::getInstance();

        $categories = $db->select('SELECT * FROM categories', []);
    ?>
    <?php include("../includes/header.php"); ?>
    <div class="wrap py-4" style="height: 100vh;">
        <div class="container">
            <!-- Thể loại -->
            <div class="user-select-none bg-white rounded">
                <!-- Loại sản phẩm -->
                <div class="position-relative">
                    <!-- nút trái -->
                    <button class="btn-scroll scroll-left bg-secondary" onclick="scrollCategories(-1)">
                        <i class="fa-solid fa-chevron-left" style="color: #ffffff;"></i>
                    </button>
                    <!-- nút phải -->
                    <button class="btn-scroll scroll-right bg-secondary" onclick="scrollCategories(1)">
                        <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                    </button>
                    <!-- slider loại -->
                    <div class="category-scroll overflow-hidden d-flex gap-3 py-2 px-4 mb-2 justify-content-lg-start">
                        <?php foreach($categories as $index => $category): ?>
                            <div class="category d-flex flex-column lh-1 p-1 <?= $index === 0 ? 'active' : '' ?>" data-name="<?= $category['name']?>">
                                <img alt="<?= $category['name'] ?>" draggable="false" src="<?= '../assets/images/theloai/' . $category['image'] ?>" />
                                <span class="text-wrap text-center" style="font-size: 12px;"><?= $category['name'] ?></span>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>
                </div>
                <!-- Lọc ... -->
                <div></div>
            </div>
            <!-- Lọc sản phẩm -->
            <div class="user-select-none mb-3 bg-white py-3 rounded d-flex">
                <!-- Trang chủ + tên thể loại -->
                <div class="ms-4 d-flex align-items-center">
                    <a class="fw-bold text-decoration-none me-3" href="#" style="cursor: pointer;">Trang chủ</a>
                    <i class="fa-solid fa-angle-right"></i>
                    <span class="ms-3" id="categoryNameSpan"><?php if (!empty($categories)) echo $categories[0]['name']; ?></span>
                </div>
                <!-- Lọc -->
                <div class="flex-grow-1 d-flex justify-content-end me-4" style="cursor: pointer;">
                    <div data-bs-target="#advancedFilterModal" data-bs-toggle="modal">
                        <i class="fa-solid fa-filter"></i>
                        <span class="fw-semibold">Bộ lọc</span>
                    </div>
                </div>
                <!-- Form lọc -->
                <div class="modal fade" id="advancedFilterModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bộ lọc nâng cao</h5>
                                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Sắp xếp -->
                                <div class="mb-3">
                                    <label class="fw-bold">Sắp xếp sản phẩm</label>
                                    <div class="d-flex flex-wrap gap-2 mt-2 filter-group" data-type="single">
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Giá cao đến thấp</div>
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Giá thấp đến cao</div>
                                    </div>
                                </div>
                                <!-- Thương hiệu -->
                                <div class="mb-3">
                                    <label class="fw-bold">Chọn thương hiệu</label>
                                    <div class="d-flex flex-wrap gap-2 mt-2 filter-group" data-type="multiple">
                                        <img class="brand-option border p-1"
                                            src="https://cdn.tgdd.vn/Brand/11/wonderfarm-05042021173431.jpg"
                                            style="height: 50px; cursor:pointer;" />
                                        <img class="brand-option border p-1"
                                            src="https://cdn.tgdd.vn/Brand/11/wonderfarm-05042021173431.jpg"
                                            style="height: 50px; cursor:pointer;" />
                                        <img class="brand-option border p-1"
                                            src="https://cdn.tgdd.vn/Brand/11/wonderfarm-05042021173431.jpg"
                                            style="height: 50px; cursor:pointer;" />
                                    </div>
                                </div>
                                <!-- Đóng gói -->
                                <div class="mb-3">
                                    <label class="fw-bold">Đóng gói</label>
                                    <div class="d-flex gap-2 mt-2">
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Dạng lon</div>
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Dạng chai</div>
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Dạng lọc</div>
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Dạng thùng</div>
                                    </div>
                                </div>
                                <!-- Thể tích -->
                                <div class="mb-3">
                                    <label class="fw-bold">Thể tích</label>
                                    <div class="d-flex gap-2 mt-2">
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Dưới 330ml</div>
                                        <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Từ 330ml–500ml</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <div class="btn btn-outline-secondary btn-reset-filters"
                                    style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                    Chọn lại</div>
                                <button class="btn btn-success">Áp dụng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="border bg-white">
                <div class="row row-cols-2 row-cols-md-4 row-cols-xl-5 p-3 g-0">
                    <div class="p-1 ps-md-3">
                        <div class="product-item border">
                            <img alt="" class="img-fluid object-fit-contain mx-auto"
                                src="../assets/images/thung-12-lon-bia-budweiser-500ml-202202191642579694.jpg" />
                            <div class="mt-2">
                                <p class="ellipsis-2-lines text-secondary mb-2 ms-2">Thùng 24 lon bia Sài Gòn Export Premium
                                </p>
                                <p class="fw-medium fs-5 ms-2">366.000đ</p>
                                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                                    href="#">MUA</a>
                            </div>
                        </div>
                    </div>
                    <div class="p-1 ps-md-3">
                        <div class="product-item border">
                            <img alt="" class="img-fluid object-fit-contain mx-auto"
                                src="../assets/images/thung-12-lon-bia-budweiser-500ml-202202191642579694.jpg" />
                            <div class="mt-2">
                                <p class="ellipsis-2-lines text-secondary mb-2 ms-2">Thùng 24 lon bia Sài Gòn Export Premium
                                </p>
                                <p class="fw-medium fs-5 ms-2">366.000đ</p>
                                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                                    href="#">MUA</a>
                            </div>
                        </div>
                    </div>
                    <div class="p-1 ps-md-3">
                        <div class="product-item border">
                            <img alt="" class="img-fluid object-fit-contain mx-auto"
                                src="../assets/images/thung-12-lon-bia-budweiser-500ml-202202191642579694.jpg" />
                            <div class="mt-2">
                                <p class="ellipsis-2-lines text-secondary mb-2 ms-2">Thùng 24 lon bia Sài Gòn Export Premium
                                </p>
                                <p class="fw-medium fs-5 ms-2">366.000đ</p>
                                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                                    href="#">MUA</a>
                            </div>
                        </div>
                    </div>
                    <div class="p-1 ps-md-3">
                        <div class="product-item border">
                            <img alt="" class="img-fluid object-fit-contain mx-auto"
                                src="../assets/images/thung-12-lon-bia-budweiser-500ml-202202191642579694.jpg" />
                            <div class="mt-2">
                                <p class="ellipsis-2-lines text-secondary mb-2 ms-2">Thùng 24 lon bia Sài Gòn Export Premium
                                </p>
                                <p class="fw-medium fs-5 ms-2">366.000đ</p>
                                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                                    href="#">MUA</a>
                            </div>
                        </div>
                    </div>
                    <div class="p-1 ps-md-3">
                        <div class="product-item border">
                            <img alt="" class="img-fluid object-fit-contain mx-auto"
                                src="../assets/images/thung-12-lon-bia-budweiser-500ml-202202191642579694.jpg" />
                            <div class="mt-2">
                                <p class="ellipsis-2-lines text-secondary mb-2 ms-2">Thùng 24 lon bia Sài Gòn Export Premium
                                </p>
                                <p class="fw-medium fs-5 ms-2">366.000đ</p>
                                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                                    href="#">MUA</a>
                            </div>
                        </div>
                    </div>
                    <!-- -->
                </div>
                <!-- Phân trnag -->
                <div class="pagination d-flex justify-content-center m-4 pe-5 gap-4 align-items-center">
                    <button class="btn-prev">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div class="border p-2">
                        <input class="pag" size="1" type="text" value="1" />
                        <span>/</span>
                        <span class="max-pag mx-2">12</span>
                    </div>
                    <button class="btn-next">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/bootstrap/js/bootstrap.min.js"> </script>
    <script>
        document.querySelector('.btn-prev').addEventListener('click', function () {
            let page = document.querySelector('.pag');
            let i = parseInt(page.value);
            if (i > 1) {
                page.value = i - 1;
            }
        });
    
        document.querySelector('.btn-next').addEventListener('click', function () {
            let page = document.querySelector('.pag');
            let max_pag = parseInt(document.querySelector('.max-pag').textContent);
            let i = parseInt(page.value);
            if (i < max_pag) {
                page.value = i + 1;
    
            }
        });
    
    
        document.addEventListener("DOMContentLoaded", function () {
            if (document.querySelector('.max-pag').textContent === '1') {
                document.querySelector('.pagination').style.setProperty('display', 'none', 'important');
            }
        });
    
        // Thêm active khi click vào thể loại
        document.querySelectorAll('.category').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelectorAll('.category').forEach(i => { i.classList.remove('active') });
                item.classList.add('active');
            });
        });
    
        // nút qua trái phải của thể loại
        function scrollCategories(direction) {
            const container = document.querySelector('.category-scroll');
            container.style.scrollBehavior = 'smooth';
            const scrollAmount = 150; // chỉnh khoảng cách cuộn
            container.scrollLeft += direction * scrollAmount;
        }
    
        // kéo scroll
        const slider = document.querySelector('.category-scroll');
        let isDown = false;
        let startX;
        let scrollLeft;
    
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            slider.style.scrollBehavior = 'auto';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
    
        slider.addEventListener('mouseleave', () => {
            isDown = false;
        });
    
        slider.addEventListener('mouseup', () => {
            isDown = false;
        });
    
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1; // tốc độ kéo
            slider.scrollLeft = scrollLeft - walk;
        });

        document.querySelector('.category-scroll').addEventListener('scroll', updateScrollButtons);
    
        // Xử lý ẩn nút left right của thể loại
        function updateScrollButtons() {
            const container = document.querySelector('.category-scroll');
            const btnLeft = document.querySelector('.scroll-left');
            const btnRight = document.querySelector('.scroll-right');
    
    
            const scrollLeft = container.scrollLeft;
            const maxScrollLeft = container.scrollWidth - container.clientWidth;
    
            if (container.scrollWidth <= container.clientWidth) {
                btnLeft.style.display = 'none';
                btnRight.style.display = 'none';
            } else {
                btnLeft.style.display = scrollLeft > 0 ? 'flex' : 'none';
                btnRight.style.display = scrollLeft < maxScrollLeft ? 'flex' : 'none';
            }
        }
    
        document.addEventListener('DOMContentLoaded', function () {
            updateScrollButtons();
            window.addEventListener('resize', updateScrollButtons);
        });
    
        document.querySelectorAll('.brand-option').forEach(img => {
            img.addEventListener('click', () => {
                img.classList.toggle('active');
            });
        });
    
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".filter-option").forEach(item => {
                item.addEventListener("click", () => {
                    item.classList.toggle("active");
                });
            });

            document.querySelectorAll(".filter-group").forEach(group => {
                const type = group.getAttribute("data-type");

                group.querySelectorAll(".filter-option").forEach(option => {
                    option.addEventListener("click", () => {
                        if (type === "single") {
                            group.querySelectorAll(".filter-option").forEach(o => o.classList.remove("active"));
                            option.classList.add("active");
                        } else {
                            option.classList.toggle("active");
                        }
                    });
                });
            });
        });

        document.querySelector('.btn-reset-filters').addEventListener('click', function () {
            // Bỏ tất cả class 'active' trong form lọc
            document.querySelectorAll('.active').forEach(el => {
                el.classList.remove('active');
            });

        });

        document.querySelectorAll('.category').forEach(item => {
            item.addEventListener('click', () => {
                // Bỏ active cũ
                document.querySelectorAll('.category').forEach(i => i.classList.remove('active'));
                item.classList.add('active');

                // Cập nhật span
                const name = item.getAttribute('data-name');
                document.getElementById('categoryNameSpan').textContent = name;
            });
        });


    
    </script>
</body>

</html>