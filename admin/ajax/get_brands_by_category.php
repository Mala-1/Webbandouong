<?php
require_once('../../includes/DBConnect.php');
$db = DBConnect::getInstance();
$categoryId = $_GET['category_id'] ?? 1;

$sql = "SELECT DISTINCT b.brand_id, b.name
        FROM brand b
        JOIN products p ON b.brand_id = p.brand_id
        WHERE p.category_id = ? AND b.is_deleted = 0
";
$brands = $db->select($sql, [$categoryId]);
// var_dump($brands);
// exit;
header('Content-Type: application/json');
echo json_encode($brands);
