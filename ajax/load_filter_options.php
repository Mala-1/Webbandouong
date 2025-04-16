<?php

require_once '../includes/DBConnect.php';

$db = DBConnect::getInstance();

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 1;




$sql = "SELECT DISTINCT
            b.brand_id,
            b.name AS brand_name
        FROM products p
        JOIN brand b ON p.brand_id = b.brand_id
        WHERE p.category_id = ?";

$category_brandImage = $db->select($sql, [$categoryId]);


// Lấy packaging_type
$packagingTypes = $db->select(
    "SELECT DISTINCT po.packaging_type
     FROM packaging_options po
     JOIN products p ON po.product_id = p.product_id
     WHERE p.category_id = ?", 
    [$categoryId]
);


ob_start();
foreach ($category_brandImage as $item): ?>
    <img class="brand-option p-1"
        src="<?= '../assets/images/Brand/' . $item['brand_image'] ?>" alt="<?= $item['brand_name'] ?>"
        style="height: 50px; cursor:pointer;" data-brand-id="<?= $item['brand_id'] ?>" />
<?php endforeach;

$brandImageHtml = ob_get_clean();

ob_start();
foreach($packagingTypes as $p): ?>
    <div class="btn filter-option" data-packaging-type="<?= $p['packaging_type'] ?>"
    style="cursor:pointer; border:1px solid #ccc; padding:6px 12px; border-radius:4px; display:inline-block;">
        Dạng <?= $p['packaging_type'] ?>
    </div>
<?php endforeach;

$packagingTypeHtml = ob_get_clean();

echo $brandImageHtml . 'SPLIT' . $packagingTypeHtml;

?>