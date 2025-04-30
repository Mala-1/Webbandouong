<?php
session_start();
require_once '../../includes/DBConnect.php';
require_once '../../includes/pagination.php';

$permissions = $_SESSION['permissions'] ?? [];
$canWriteOrder = in_array('write', $permissions['Quản lý đơn hàng'] ?? []);
$canDeleteOrder = in_array('delete', $permissions['Quản lý đơn hàng'] ?? []);
$canReadOrder = in_array('read', $permissions['Quản lý đơn hàng'] ?? []);

$db = DBConnect::getInstance();

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search_id = $_GET['search_id'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$status = $_GET['status'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

$whereClauses = [];
$params = [];

if ($search_id !== '') {
    $whereClauses[] = 'o.order_id = ?';
    $params[] = $search_id;
}

if ($price_min !== '') {
    $whereClauses[] = 'o.total_price >= ?';
    $params[] = $price_min;
}

if ($price_max !== '') {
    $whereClauses[] = 'o.total_price <= ?';
    $params[] = $price_max;
}

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

// Gộp điều kiện WHERE
$whereSql = count($whereClauses) > 0 ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT SQL_CALC_FOUND_ROWS o.*, pm.name AS payment_method_name, u.username AS username,
        GROUP_CONCAT(
          CONCAT(
            p.product_id, '||', po.packaging_option_id, '||', p.name, '||', op.quantity, '||', op.price, '||', po.packaging_type, '||', po.unit_quantity
          ) SEPARATOR '##'
        ) AS product_details 
        FROM orders o
        LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
        LEFT JOIN order_details op ON o.order_id = op.order_id
        LEFT JOIN products p ON op.product_id = p.product_id
        LEFT JOIN packaging_options po ON op.packaging_option_id = po.packaging_option_id
        LEFT JOIN users u ON o.user_id = u.user_id
        $whereSql
        GROUP BY o.order_id DESC
        LIMIT $limit OFFSET $offset";




$orders = $db->select($sql, $params);

$totalQuery = "SELECT FOUND_ROWS()";
$totalResult = $db->select($totalQuery);
$totalOrders = $totalResult[0]['FOUND_ROWS()'];

// Tổng số trang
$totalPages = ceil($totalOrders / $limit);

// Khởi tạo phân trang
$pagination = new Pagination($totalOrders, $limit, $page);

ob_start();
foreach ($orders as $order):
    $orderDetails = [
        'customer_name' => htmlspecialchars($order['username']),
        'status' => htmlspecialchars($order['status']),
        'payment_method' => htmlspecialchars($order['payment_method_name']),
        'order_date' => htmlspecialchars($order['created_at']),
        'total_price' => number_format($order['total_price'], 0, ',', '.'),
        'delivery_address' => htmlspecialchars($order['shipping_address']),
        'products' => array_map(function ($productDetail) {
            $details = explode('||', $productDetail);
            return [
                'product_id' => $details[0] ?? null,
                'packaging_option_id' => $details[1] ?? null,
                'name' => $details[2] ?? '',
                'quantity' => $details[3] ?? 0,
                'price' => $details[4] ?? 0,
                'packaging_type' => $details[5] ?? '',
                'unit_quantity' => $details[6] ?? ''
            ];
        }, explode('##', $order['product_details'] ?? ''))
    ];
?>

    <tr>
        <td><?= $order['order_id'] ?></td>
        <td><?= htmlspecialchars($order['user_id']) ?></td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td><?= number_format($order['total_price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= htmlspecialchars($order['payment_method_name'] ?? 'Không xác định') ?></td> <!-- ✅ Thêm phương thức thanh toán -->
        <td><?= htmlspecialchars($order['shipping_address'] ?? 'Không có') ?></td>
        <td><?= htmlspecialchars($order['created_at']) ?></td>
        <?php if ($canWriteOrder || $canDeleteOrder || $canReadOrder): ?>
            <td class="action-icons">
                <?php if ($canReadOrder): ?>
                    <i class="fas fa-eye text-info btn-view-order fa-lg"
                        style="cursor: pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#orderDetailsModal"
                        data-bs-toggle="tooltip"
                        title="Xem chi tiết đơn hàng"
                        data-order-details='<?= json_encode($orderDetails) ?>'></i>
                <?php endif; ?>
                <?php if ($canWriteOrder && $orderDetails['status'] == 'Chờ xử lý'): ?>
                    <i class="fas fa-pen text-primary btn-edit-order ms-3 fa-lg" style="cursor: pointer;"
                        data-id="<?= $order['order_id'] ?>"
                        data-user="<?= htmlspecialchars($order['user_id']) ?>"
                        data-status="<?= htmlspecialchars($order['status']) ?>"
                        data-address="<?= htmlspecialchars($order['shipping_address']) ?>"
                        data-payment="<?= htmlspecialchars($order['payment_method_id']) ?>"
                        data-note="<?= htmlspecialchars($order['note'] ?? '') ?>"
                        data-total-price="<?= $order['total_price'] ?>"
                        data-order-details='<?= json_encode($orderDetails) ?>'></i>
                <?php endif; ?>

                <?php if ($canDeleteOrder && $orderDetails['status'] != 'Đã giao hàng' && $orderDetails['status'] != 'Đã xác nhận'): ?>
                    <i class="fas fa-trash fa-lg text-danger btn-delete-order ms-3" style="cursor: pointer;"
                        data-id="<?= $order['order_id'] ?>" data-bs-toggle="modal" data-bs-target="#modalXoaDonHang"></i>
                <?php endif; ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach;

$orderHtml = ob_get_clean();

$data = [
    'orderHtml' => $orderHtml,
    'pagination' => ($totalPages > 1) ? $pagination->render([], 'orderpage') : null
];

header('Content-Type: application/json');
echo json_encode($data);
