<?php
require_once '../../includes/DBConnect.php';
require_once '../../vendor/autoload.php'; // cần thư viện PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = DBConnect::getInstance();

// 1. Lấy tham số lọc
$search_id = $_GET['search_id'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$status = $_GET['status'] ?? '';

$whereClauses = [];
$params = [];

if ($search_id !== '') {
    $whereClauses[] = 'io.import_order_id = ?';
    $params[] = $search_id;
}
if ($price_min !== '') {
    $whereClauses[] = 'io.total_price >= ?';
    $params[] = $price_min;
}
if ($price_max !== '') {
    $whereClauses[] = 'io.total_price <= ?';
    $params[] = $price_max;
}
if ($from_date !== '') {
    $whereClauses[] = 'DATE(io.created_at) >= ?';
    $params[] = $from_date;
}
if ($to_date !== '') {
    $whereClauses[] = 'DATE(io.created_at) <= ?';
    $params[] = $to_date;
}
if ($status !== '') {
    $whereClauses[] = 'io.status = ?';
    $params[] = $status;
}

$whereSQL = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// 2. Query dữ liệu
$sql = "SELECT io.import_order_id, s.name AS supplier_name, u.username AS user_name, io.total_price, io.created_at, io.status
        FROM import_order io
        LEFT JOIN supplier s ON io.supplier_id = s.supplier_id
        LEFT JOIN users u ON io.user_id = u.user_id
        $whereSQL
        ORDER BY io.created_at DESC";

$receipts = $db->select($sql, $params);

// 3. Tạo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header
$sheet->fromArray(['Mã phiếu nhập', 'Nhà cung cấp', 'Người nhập', 'Tổng giá', 'Ngày nhập', 'Tình trạng'], NULL, 'A1');

// Data
$row = 2;
foreach ($receipts as $receipt) {
    $sheet->fromArray([
        $receipt['import_order_id'],
        $receipt['supplier_name'],
        $receipt['user_name'],
        number_format($receipt['total_price'], 0, ',', '.'),
        $receipt['created_at'],
        $receipt['status']
    ], NULL, "A{$row}");
    $row++;
}

// 4. Xuất file
$filename = 'phieu_nhap_filtered_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
