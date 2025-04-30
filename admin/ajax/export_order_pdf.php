<?php
require_once '../../includes/DBConnect.php';
require_once '../../vendor/autoload.php';

use Mpdf\Mpdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderData = json_decode(file_get_contents('php://input'), true);

    if (!$orderData || !isset($orderData['customer_name'])) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
        exit;
    }

    $mpdf = new Mpdf();
    $mpdf->SetTitle("Hóa đơn đơn hàng");

    $html = '
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .invoice-box {
            width: 100%;
            padding: 10px;
            border: 1px solid #eee;
            font-size: 12pt;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .info p {
            margin: 4px 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            padding-top: 10px;
        }
    </style>

    <div class="invoice-box">
        <div class="title">HÓA ĐƠN BÁN HÀNG</div>

        <div class="info">
            <p><strong>Khách hàng:</strong> ' . htmlspecialchars($orderData['customer_name']) . '</p>
            <p><strong>Trạng thái:</strong> ' . htmlspecialchars($orderData['status']) . '</p>
            <p><strong>Phương thức thanh toán:</strong> ' . htmlspecialchars($orderData['payment_method']) . '</p>
            <p><strong>Ngày đặt:</strong> ' . htmlspecialchars($orderData['order_date']) . '</p>
            <p><strong>Địa chỉ giao hàng:</strong> ' . htmlspecialchars($orderData['delivery_address']) . '</p>
        </div>

        <h4>Chi tiết sản phẩm</h4>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đóng gói</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($orderData['products'] as $product) {
        $html .= '<tr>
            <td>' . htmlspecialchars($product['name']) . '</td>
            <td>' . htmlspecialchars($product['packaging_type']) . ' - ' . htmlspecialchars($product['unit_quantity']) . '</td>
            <td>' . intval($product['quantity']) . '</td>
            <td>' . number_format($product['price'], 0, ',', '.') . ' VNĐ</td>
        </tr>';
    }

    $html .= '</tbody>
        </table>
        <p class="total">Tổng tiền: ' . htmlspecialchars($orderData['total_price']) . ' VNĐ</p>
    </div>';

    $mpdf->WriteHTML($html);
    $mpdf->Output('hoa_don.pdf', \Mpdf\Output\Destination::INLINE);
}
