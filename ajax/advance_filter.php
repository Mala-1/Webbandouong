<?php

require_once '../includes/DBConnect.php';

$db = DBConnect::getInstance();

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 1;


$sql = 'SELECT 
                    c.category_id,
                    c.name AS category_name,
                    b.brand_id,
                    b.name AS brand_name,
                    b.image AS brand_image
                FROM categories c
                JOIN products p ON p.category_id = c.category_id
                JOIN brand b ON p.brand_id = b.brand_id
                WHERE c.category_id = ?
                GROUP BY b.brand_id;';

$category_brandImage = $db->select($sql, [$categoryId]);

ob_start();
foreach ($category_brandImage as $item): ?>
    <img class="brand-option border p-1"
        src="<?= '../assets/images/Brand/' . $item['brand_image'] ?>"
        style="height: 50px; cursor:pointer;" />
<?php endforeach;

echo ob_get_clean();

?>