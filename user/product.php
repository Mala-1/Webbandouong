<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Document</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/product.css">

</head>

<body>
    <?php
    require_once './includes/DBConnect.php';

    $db = DBConnect::getInstance();

    $categories = $db->select('SELECT * FROM categories', []);


    ?>
    <div class="wrap flex-grow-1 py-4">
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
                        <?php foreach ($categories as $index => $category): ?>
                            <div class="category d-flex flex-column lh-1 p-1 <?= $index === 0 ? 'active' : '' ?>" data-name="<?= $category['name'] ?>" data-category-id="<?= $category['category_id'] ?>">
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
                    <span class="ms-3"
                        id="categoryNameSpan"><?php if (!empty($categories)) echo $categories[0]['name']; ?></span>
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
                                        <div class="btn btn-outline-secondary filter-option" data-sort="desc"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Giá cao đến thấp</div>
                                        <div class="btn btn-outline-secondary filter-option" data-sort="asc"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Giá thấp đến cao</div>
                                    </div>
                                </div>
                                <!-- Thương hiệu -->
                                <div class="mb-3">
                                    <label class="fw-bold">Chọn thương hiệu</label>
                                    <div class="brand-wrap d-flex flex-wrap gap-3 mt-2 filter-group" data-type="multiple">

                                    </div>
                                </div>
                                <!-- Đóng gói -->
                                <div class="mb-3">
                                    <label class="fw-bold">Đóng gói</label>
                                    <div class="packaging_type-wrap d-flex gap-2 mt-2 filter-group" data-type="multiple">
                                        <!-- <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            Dạng lon</div> -->
                                    </div>
                                </div>
                                <!-- Thể tích -->
                                <div class="mb-3">
                                    <label class="fw-bold">Thể tích</label>
                                    <div class="size-wrap d-flex gap-2 mt-2 filter-group" data-type="multiple">
                                        <!-- <div class="btn btn-outline-secondary filter-option"
                                            style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                            330ml</div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <div class="btn btn-outline-secondary btn-reset-filters"
                                    style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
                                    Chọn lại</div>
                                <button class="btn btn-success btn-filter">Áp dụng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="productScrollWrapper" class="border bg-white">
                <div class="product-wrap row row-cols-2 row-cols-md-4 row-cols-xl-5 p-3 g-0">

                    <!-- <div class="p-1 ps-md-3">
                        <div class="product-item border">
                            <img alt="" class="img-fluid object-fit-contain mx-auto"
                                src="../assets/images/thung-12-lon-bia-budweiser-500ml-202202191642579694.jpg" />
                            <div class="mt-2">
                                <p class="ellipsis-2-lines text-secondary mb-2 ms-2">Thùng 24 lon bia Sài Gòn Export
                                    Premium
                                </p>
                                <p class="fw-medium fs-5 ms-2">366.000đ</p>
                                <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                                    href="#">MUA</a>
                            </div>
                        </div>
                    </div> -->


                    <!-- -->
                </div>
                <!-- Phân trnag -->
                <div class="pagination-wrap"></div>
            </div>
        </div>
    </div>
    
    <script src="../assets/javascript/product.js"></script>
    <script src="../assets/javascript/pagination.js"></script>
</body>

</html>