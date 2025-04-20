<?php
require_once '../../includes/DBConnect.php';
$db = DBConnect::getInstance();

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$sql = "
SELECT DATE(created_at) as ngay, SUM(total_amount) as doanhthu
FROM orders
WHERE created_at BETWEEN ? AND ?
AND status = 'Hoàn thành'
GROUP BY DATE(created_at)
ORDER BY ngay
";

$rows = $db->select($sql, [$start_date, $end_date]);

$labels = [];
$revenues = [];
$total = 0;

foreach ($rows as $row) {
    $labels[] = $row['ngay'];
    $revenues[] = (int)$row['doanhthu'];
    $total += (int)$row['doanhthu'];
}

echo json_encode([
    'labels' => $labels,
    'revenues' => $revenues,
    'total' => $total,
    'total_formatted' => number_format($total) . 'đ'
]);
