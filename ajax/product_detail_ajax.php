<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$packagingOptionId = $_POST['packaging_option_id'] ?? 0;

$product = $db->selectOne("
    SELECT 
        p.name,
        p.description,
        po.image,
        CASE 
            WHEN po.price IS NULL OR po.price = 0 THEN p.price
            ELSE po.price
        END AS price
    FROM packaging_options po
    JOIN products p ON p.product_id = po.product_id
    WHERE po.packaging_option_id = ?
", [$packagingOptionId]);

if ($product) {
    echo json_encode([
        'status' => 'success',
        'name' => $product['name'],
        'image' => $product['image'],
        'price' => number_format($product['price'], 0, ',', '.'),
        'description' => $product['description']
    ]);
} else {
    echo json_encode(['status' => 'error']);
}