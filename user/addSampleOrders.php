<?php
include '../includes/DBConnect.php';

// Kết nối đến cơ sở dữ liệu
$db = DBConnect::getInstance();

// Dữ liệu mẫu để thêm vào bảng orders
$sampleOrders = [
    [
        'user_id' => 1,
        'status' => 'Chờ xử lý',
        'total_price' => 500000,
        'shipping_address' => '123 Đường ABC, Quận 1, TP.HCM',
        'created_at' => date('Y-m-d H:i:s'),
        'payment_method_id' => 2
    ],
    [
        'user_id' => 2,
        'status' => 'Đã xác nhận',
        'total_price' => 750000,
        'shipping_address' => '456 Đường DEF, Quận 3, TP.HCM',
        'created_at' => date('Y-m-d H:i:s'),
        'payment_method_id' => 1
    ],
    [
        'user_id' => 3,
        'status' => 'Đã giao hàng',
        'total_price' => 300000,
        'shipping_address' => '789 Đường GHI, Quận 5, TP.HCM',
        'created_at' => date('Y-m-d H:i:s'),
        'payment_method_id' => 3
    ]
];

// Thêm dữ liệu mẫu vào bảng orders
foreach ($sampleOrders as $order) {
    $query = "INSERT INTO orders (user_id, status, total_price, shipping_address, created_at, payment_method_id) VALUES (?, ?, ?, ?, ?, ?)";
    $db->execute($query, [
        $order['user_id'],
        $order['status'],
        $order['total_price'],
        $order['shipping_address'],
        $order['created_at'],
        $order['payment_method_id']
    ]);
}

echo "Dữ liệu mẫu đã được thêm vào bảng orders.";