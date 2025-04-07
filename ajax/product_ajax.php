<?php
    require_once '../includes/DBConnect.php';
    require_once '../includes/pagination.php';

    $db = DBConnect::getInstance();

    // Phân trang
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Thể loại
    $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

    // Lọc
    

    $products = $db->select("SELECT SQL_CALC_FOUND_ROWS
                            p.product_id,
                            p.name,
                            po.packaging_option_id,
                            po.packaging_type,
                            po.unit_quantity,
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
                        JOIN packaging_options po ON po.product_id = p.product_id
                        WHERE p.category_id = ?
                        ORDER BY p.product_id, po.packaging_option_id
                        LIMIT $limit OFFSET $offset", [$categoryId]);

    
    $totalProductQuery = "SELECT FOUND_ROWS()";
    $totalProductResult = $db->select($totalProductQuery);
    $totalProduct = $totalProductResult[0]['FOUND_ROWS()'];

    // Tổng số trang
    $totalPages = ceil($totalProduct / $limit);

    // Khởi tạo phân tragn
    $pagination = new Pagination($totalProduct, $limit, $page);
    $baseQueryParams = [
        'page' => $page
    ];


    function formatProductName($packaging_type, $unit_quantity,$product_name) {
        $packaging = trim($packaging_type . ' ' . $unit_quantity);
    
        // Loại trùng nếu packaging_type đã nằm trong unit_quantity
        if (stripos($unit_quantity, $packaging_type) !== false) {
            $packaging = $unit_quantity;
        }
    
        // Với unit_quantity là "1 lon", "1 chai", có thể tối giản
        if (preg_match('/^1\s+\w+$/', $unit_quantity)) {
            $packaging = $packaging_type; // chỉ in "Lon" hoặc "Chai"
        }
    
        return "{$packaging} {$product_name}";
    }

    ob_start();
    foreach($products as $product): ?>
        <div class="p-1 ps-md-3 mb-3">
            <div class="product-item border pt-2 shadow">
                <img alt="<?= $product['name'] ?>" class="img-fluid object-fit-contain mx-auto"
                    src=<?= '../assets/images/SanPham/' . $product['image']?>>
                <div class="mt-2">
                    <p class="ellipsis-2-lines text-secondary mb-2 ms-2">
                        <?= formatProductName($product['packaging_type'], $product['unit_quantity'], $product['name']) ?>
                    </p>
                    <p class="fw-medium fs-5 ms-2"><?= number_format($product['price']) .'đ' ?></p>
                    <a class="btn-buy text-decoration-none text-black d-block w-100 text-center py-2"
                        href="#">MUA</a>
                </div>
            </div>
        </div>
    <?php endforeach;

    $productsHtml = ob_get_clean();

    echo $productsHtml . ($totalPages > 1 ? 'SPLIT' . $pagination->render([], 'pageproduct') : '');
    

    
?>