<?php
require_once '../../includes/DBConnect.php';
require_once '../../vendor/autoload.php'; // cần thư viện PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = DBConnect::getInstance();

// Lấy danh sách phiếu nhập (nên join supplier và user để lấy tên)
$sql = "SELECT io.import_order_id, s.name AS supplier_name, u.username AS user_name, io.total_price, io.created_at, io.status
        FROM import_order io
        LEFT JOIN supplier s ON io.supplier_id = s.supplier_id
        LEFT JOIN users u ON io.user_id = u.user_id";

$receipts = $db->select($sql);

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
        $receipt['total_price'],
        $receipt['created_at'],
        $receipt['status']
    ], NULL, "A{$row}");
    $row++;
}

// Xuất file
$filename = 'phieu_nhap_all.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
