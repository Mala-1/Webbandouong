<?php
require_once '../../includes/DBConnect.php';
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

// Nếu cần lọc theo status, from_date, to_date...
$status = $_GET['status'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

$whereClauses = [];
$params = [];

if ($status !== '') {
    $whereClauses[] = 'o.status = ?';
    $params[] = $status;
}
if ($from_date !== '') {
    $whereClauses[] = 'DATE(o.created_at) >= ?';
    $params[] = $from_date;
}
if ($to_date !== '') {
    $whereClauses[] = 'DATE(o.created_at) <= ?';
    $params[] = $to_date;
}

$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT o.order_id, u.username, o.total_price, o.status, o.shipping_address, o.created_at, pm.name AS payment_method
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
        $whereSql
        ORDER BY o.created_at DESC";

$orders = $db->select($sql, $params);

// Khởi tạo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Danh sách đơn hàng');

// Tiêu đề cột
$sheet->fromArray([
    'Mã đơn', 'Người đặt', 'Trạng thái', 'Tổng giá', 'Phương thức thanh toán', 'Địa chỉ giao hàng', 'Ngày đặt'
], NULL, 'A1');

// Dữ liệu
$row = 2;
foreach ($orders as $order) {
    $sheet->setCellValue('A' . $row, $order['order_id']);
    $sheet->setCellValue('B' . $row, $order['username']);
    $sheet->setCellValue('C' . $row, $order['status']);
    $sheet->setCellValue('D' . $row, number_format($order['total_price'], 0, ',', '.') . ' VNĐ');
    $sheet->setCellValue('E' . $row, $order['payment_method']);
    $sheet->setCellValue('F' . $row, $order['shipping_address']);
    $sheet->setCellValue('G' . $row, $order['created_at']);
    $row++;
}

// Xuất file
$filename = 'danh_sach_don_hang_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
