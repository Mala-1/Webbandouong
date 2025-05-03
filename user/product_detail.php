<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/assets/images/strarbucks.jpg">

    <style>
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
            left: 0;
        }

        .scroll-right {
            bottom: -8px;
            right: 0;
        }

        .scroll-left-img {
            bottom: -8px;
            left: 0;
        }

        .scroll-right-img {
            bottom: -8px;
            right: 0;
        }

        .buynow:hover {
            background-color: #000000;
            color: white;
        }

        .add-to-cart {
            color: white;
        }

        .add-to-cart:hover {
            border: 1px solid black;
            background-color: #fff !important;
            color: black !important;
        }

        .add-to-cart:active {
            background-color: #000000 !important;
            color: white !important;
        }

        .notice-add-to-cart {
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        .notice-add-to-cart.show {
            transform: translate(-50%, -50%) scale(1);
            /* Hiện với scale 1 */
            opacity: 1 !important;
        }

        .img-scroll .item {
            flex: 0 0 calc(100%);
            max-width: calc(100%);
        }

        .img-scroll img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        body {
            background-color: #e9edf0;
        }
    </style>
</head>

<body>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <?php if (isset($_SESSION['login_success'])): ?>
        <div class="alert alert-success text-center">
            <?= $_SESSION['login_success'] ?>
        </div>
        <?php unset($_SESSION['login_success']); ?>
    <?php endif; ?>


    <?php
    require_once '../includes/DBConnect.php';
    $db = DBConnect::getInstance();

    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 1;
    $packaging_option_id = isset($_GET['packaging_option_id']) ? $_GET['packaging_option_id'] : 11;

    $products = $db->select("SELECT p.product_id,
                            p.name,
                            p.size,
                            po.packaging_option_id,
                            po.packaging_type,
                            po.unit_quantity,
                            p.description,
                            po.stock,
                            COALESCE(
                                po.image,
                                (
                                    SELECT pi.image 
                                    FROM product_images pi 
                                    WHERE pi.product_id = p.product_id 
                                    ORDER BY pi.image ASC 
                                    LIMIT 1
                                )
                            ) AS image,
                            CASE 
                                WHEN po.price IS NULL OR po.price = 0 THEN p.price
                                ELSE po.price
                            END AS price
                        FROM products p
                        LEFT JOIN packaging_options po ON po.product_id = p.product_id
                        WHERE p.product_id = ? AND po.is_deleted = 0 AND (
                                po.stock > 0 
                                OR EXISTS (
                                    SELECT 1
                                    FROM packaging_options po2
                                    WHERE po2.product_id = po.product_id
                                    AND CAST(SUBSTRING_INDEX(po2.unit_quantity, ' ', 1) AS UNSIGNED) > CAST(SUBSTRING_INDEX(po.unit_quantity, ' ', 1) AS UNSIGNED)
                                    AND po2.stock > 0
                                    AND (po2.is_deleted = 0 OR po2.is_deleted IS NULL)
                                )
                            )", [$product_id]);


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


    $sql = "
            (
                SELECT po.image AS image, 0 AS priority
                FROM packaging_options po
                WHERE po.product_id = ? AND po.image IS NOT NULL AND po.packaging_option_id = ?
            )
            UNION ALL
            (
                SELECT pi.image AS image, 1 AS priority
                FROM product_images pi
                WHERE pi.product_id = ?
            )
            ORDER BY priority, image ASC
        ";

    $images = $db->select($sql, [$product_id, $packaging_option_id, $product_id]);

    ?>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-3">
        <div>
            <div class="row">
                <div class="col-8 p-2">
                    <div class="position-relative user-select-none bg-white border rounded p-4">
                        <!-- nút trái -->
                        <button class="btn-scroll scroll-left-img bg-secondary" onclick="scrollImg(-1)">
                            <i class="fa-solid fa-chevron-left" style="color: #ffffff;"></i>
                        </button>
                        <!-- nút phải -->
                        <button class="btn-scroll scroll-right-img bg-secondary" onclick="scrollImg(1)">
                            <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                        </button>

                        <div class="img-scroll overflow-hidden d-flex gap-3 p-2 mb-2 justify-content-lg-start">

                            <?php foreach ($images as $image): ?>
                                <div class="item">
                                    <img src="../assets/images/SanPham/<?= $image['image'] ?>"
                                        draggable="false" alt="" class="w-100 h-100">
                                </div>
                            <?php endforeach; ?>


                        </div>
                    </div>
                </div>
                <div class="col-4 p-2">
                    <div class="bg-white py-2 px-2 h-100 d-flex flex-column border rounded">
                        <h3 class="mb-4 mt-1">
                            <?php foreach ($products as $product):
                                if ($product['packaging_option_id'] == $packaging_option_id) {
                                    echo formatProductName($product['packaging_type'], $product['unit_quantity'], $product['name']);
                                }
                            endforeach; ?>
                        </h3>
                        <div class="position-relative user-select-none">
                            <!-- nút trái -->
                            <button class="btn-scroll scroll-left bg-secondary" onclick="scrollPackagingOption(-1)">
                                <i class="fa-solid fa-chevron-left" style="color: #ffffff;"></i>
                            </button>
                            <!-- nút phải -->
                            <button class="btn-scroll scroll-right bg-secondary" onclick="scrollPackagingOption(1)">
                                <i class="fa-solid fa-chevron-right" style="color: #ffffff;"></i>
                            </button>

                            <div
                                class="packagion_option_scroll overflow-hidden d-flex gap-3 p-2 mb-2 justify-content-lg-start">
                                <?php foreach ($products as $product): ?>
                                    <a class="packaging-option-item d-flex flex-column align-items-center border rounded text-decoration-none text-black user-select-none"
                                        style="cursor: pointer;" href="?product_id=<?= $product['product_id'] ?>&packaging_option_id=<?= $product['packaging_option_id'] ?>">
                                        <img src="../assets/images/SanPham/<?= $product['image'] ?>"
                                            alt="" draggable="false" style="width: 110px; height: 110px;" class="object-fit-contain">
                                        <?php if ($product['packaging_option_id'] == $packaging_option_id): ?>
                                            <i class="fa-regular fa-circle-dot mt-2 mb-1"></i>
                                        <?php else: ?>
                                            <i class="fa-regular fa-circle mt-2 mb-1"></i>
                                        <?php endif; ?>
                                        <span class="m-0"><?= number_format($product['price']) ?>đ</span>
                                    </a>
                                <?php endforeach; ?>

                            </div>
                        </div>

                        <div class="my-3 d-flex align-items-center gap-2">
                            <label>Số lượng</label>
                            <input type="number" value="1" min="1" class="quantity form-control w-auto">
                        </div>

                        <div class="mt-4 d-grid gap-2 d-md-flex justify-content-md-between align-items-center">
                            <!-- Mua ngay -->
                            <a href="#"
                                class="btn btn-outline-dark btnBuyNow w-100 w-md-50 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-bolt me-2"></i> Mua ngay
                            </a>

                            <!-- Thêm vào giỏ -->
                            <button
                                class="add-to-cart btn w-100 w-md-50 bg-black text-white d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                            </button>
                        </div>
                        <!-- modal thêm thành công -->
                        <div class="notice-add-to-cart position-absolute top-50 start-50 d-flex flex-column justify-content-center align-items-center p-5 rounded w-auto opacity-0" style="background-color: rgba(0, 0, 0, 0.8);">
                            <i class="fa-solid fa-circle-check fa-3x mb-2" style="color: #ffffff;"></i>
                            <span class="text-white text-center">Đã thêm vào giỏ hàng</span>
                        </div>


                        <!-- Modal yêu cầu đăng nhập -->
                        <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-center">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="loginRequiredModalLabel">Thông báo</h5>
                                    </div>
                                    <div class="modal-body">
                                        Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <a href="../user/login.php" class="btn btn-primary">Đăng nhập</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-12 p-2">
                    <div class=" bg-white rounded p-4 border">
                        <h3 class="text-decoration-underline mb-3">Mô tả sản phẩm</h3>
                        <p style="white-space: pre-line;"><?= $products[0]['description'] ?></p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

        const packagingStocks = {
            <?php foreach ($products as $product): ?>
                <?= $product['packaging_option_id'] ?>: <?= $product['stock'] ?? 0 ?>,
            <?php endforeach; ?>
        };

        const price = <?php
                        foreach ($products as $product) {
                            if ($product['packaging_option_id'] == $packaging_option_id) {
                                echo $product['price'];
                                break;
                            }
                        }
                        ?>;
    </script>
    <script>
        function scrollPackagingOption(direction) {
            const container = document.querySelector('.packagion_option_scroll');
            container.style.scrollBehavior = 'smooth';
            const scrollAmount = 150; // chỉnh khoảng cách cuộn
            container.scrollLeft += direction * scrollAmount;
        }

        // kéo scroll
        const slider = document.querySelector('.packagion_option_scroll');
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

        document.querySelector('.packagion_option_scroll').addEventListener('scroll', updateScrollButtons);

        function updateScrollButtons() {
            const container = document.querySelector('.packagion_option_scroll');
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

        updateScrollButtons();




        function scrollImg(direction) {
            const container = document.querySelector('.img-scroll');
            const items = container.querySelectorAll('.item');
            let itemWidth = 0;
            if (items.length >= 2) {
                const first = items[0];
                const second = items[1];
                itemWidth = second.offsetLeft - first.offsetLeft;
            } else if (items.length === 1) {
                itemWidth = items[0].offsetWidth;
            }


            container.scrollBy({
                left: direction * itemWidth,
                behavior: 'smooth'
            });

            // Cập nhật lại trạng thái hiển thị nút
            setTimeout(updateScrollButtonsImg, 50);
        }

        document.querySelector('.img-scroll').addEventListener('scroll', updateScrollButtonsImg);

        function updateScrollButtonsImg() {
            const container = document.querySelector('.img-scroll');
            const btnLeft = document.querySelector('.scroll-left-img');
            const btnRight = document.querySelector('.scroll-right-img');


            const scrollLeft = container.scrollLeft;
            const maxScrollLeft = container.scrollWidth - container.clientWidth;

            if (container.scrollWidth <= container.clientWidth) {
                btnLeft.style.display = 'none';
                btnRight.style.display = 'none';
            } else {
                btnLeft.style.display = scrollLeft > 0 ? 'flex' : 'none';
                btnRight.style.display = (scrollLeft + 1) < maxScrollLeft ? 'flex' : 'none';
            }
        }

        updateScrollButtonsImg();

        const container = document.querySelector('.packagion_option_scroll');

        container.addEventListener('click', function(e) {
            const clickedItem = e.target.closest('.packaging-option-item');
            if (!clickedItem) return; // click không trúng item

            // Reset icon tất cả
            container.querySelectorAll('.packaging-option-item i').forEach(icon => {
                icon.classList.remove('fa-circle-dot');
                icon.classList.add('fa-circle');
            });

            // Gán icon cho item đang click
            const icon = clickedItem.querySelector('i');
            icon.classList.remove('fa-circle');
            icon.classList.add('fa-circle-dot');

            // (Tùy chọn) Highlight border
            container.querySelectorAll('.packaging-option-item').forEach(item => {
                item.classList.remove('selected');
            });
            clickedItem.classList.add('selected');
        });

        document.querySelector(".add-to-cart").addEventListener("click", function() {
            if (!isLoggedIn) {
                // Hiện modal yêu cầu đăng nhập
                const loginModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
                loginModal.show();
                return;
            }

            const quantityInput = document.querySelector(".quantity");
            const quantity = parseInt(quantityInput.value);
            const selectedOptionId = <?= $packaging_option_id ?>;

            if (!quantity || quantity <= 0) {
                alert("Vui lòng nhập số lượng hợp lệ.");
                return;
            }

            const packaging_option_id = <?= $packaging_option_id ?>;

            fetch('../ajax/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        packaging_option_id: packaging_option_id,
                        quantity: quantity,
                        price: price
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log(data)
                    if (data.success) {
                        // Hiện popup đã thêm thành công
                        let popup = document.querySelector(".notice-add-to-cart");
                        popup.classList.add("show");
                        setTimeout(() => {
                            popup.classList.remove("show");
                        }, 2000);
                    } else {
                        alert(data.message);
                    }
                });
        });

        document.querySelector(".btnBuyNow").addEventListener("click", function() {
            if (!isLoggedIn) {
                // Hiện modal yêu cầu đăng nhập
                const loginModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
                loginModal.show();
                return;
            }

            const quantityInput = document.querySelector(".quantity");
            const quantity = parseInt(quantityInput.value);
            const selectedOptionId = <?= $packaging_option_id ?>;

            if (!quantity || quantity <= 0) {
                alert("Vui lòng nhập số lượng hợp lệ.");
                return;
            }

            const packaging_option_id = <?= $packaging_option_id ?>;

            fetch('../ajax/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        packaging_option_id: packaging_option_id,
                        quantity: quantity,
                        price: price
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "../user/cart.php";
                    } else {
                        alert(data.message);
                    }
                });
        });
    </script>
</body>

</html>