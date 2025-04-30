<?php
require_once '../../includes/DBConnect.php';
require_once '../../vendor/autoload.php'; // nạp mPDF

use Mpdf\Mpdf;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Chỉ hỗ trợ POST";
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['receipt']) || empty($data['products'])) {
    http_response_code(400);
    echo "Thiếu dữ liệu!";
    exit;
}

// Tách dữ liệu
$receipt = $data['receipt'];
$products = $data['products'];

$mpdf = new Mpdf();

// Bắt đầu HTML
$html = '
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .header { text-align: center; margin-bottom: 20px; }
    .info { margin-bottom: 10px; }
    .info p { margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    th { background-color: #f5f5f5; }
</style>

<div class="header">
    <h2>PHIẾU NHẬP HÀNG</h2>
</div>

<div class="info">
    <p><strong>Mã phiếu nhập:</strong> ' . htmlspecialchars($receipt['import_order_id']) . '</p>
    <p><strong>Nhà cung cấp:</strong> ' . htmlspecialchars($receipt['supplier_name']) . '</p>
    <p><strong>Người nhập:</strong> ' . htmlspecialchars($receipt['username']) . '</p>
    <p><strong>Ngày nhập:</strong> ' . htmlspecialchars(date('d/m/Y', strtotime($receipt['created_at']))) . '</p>
</div>

<table>
    <thead>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Kiểu đóng gói</th>
            <th>Số lượng</th>
            <th>Đơn giá (VNĐ)</th>
            <th>Thành tiền (VNĐ)</th>
        </tr>
    </thead>
    <tbody>';

$total = 0;
foreach ($products as $product) {
    $subtotal = $product['quantity'] * $product['price'];
    $total += $subtotal;

    $html .= '
        <tr>
            <td>' . htmlspecialchars($product['product_name']) . '</td>
            <td>' . htmlspecialchars($product['packaging_type']) . ' - ' . htmlspecialchars($product['unit_quantity']) . '</td>
            <td>' . (int)$product['quantity'] . '</td>
            <td>' . number_format($product['price'], 0, ',', '.') . '</td>
            <td>' . number_format($subtotal, 0, ',', '.') . '</td>
        </tr>';
}

$html .= '
        <tr>
            <td colspan="4" style="text-align: right;"><strong>Tổng cộng:</strong></td>
            <td><strong>' . number_format($total, 0, ',', '.') . ' VNĐ</strong></td>
        </tr>
    </tbody>
</table>
';

// In ra PDF
$mpdf->WriteHTML($html);

// Xuất luôn file dạng download
$filename = 'phieu_nhap_' . $receipt['import_order_id'] . '.pdf';
$mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
