<?php
session_start();
include '../includes/DBConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $payment_method_id = $_POST['payment_method_id'] ?? null;
    $grandTotal = $_SESSION['grandTotal'] ?? 0;
    $cartItems = $_SESSION['filteredCartItems'] ?? [];

    // Debugging: Print data to the screen before inserting into the database
    echo '<pre>';
    echo 'User ID: ' . htmlspecialchars($user_id) . "\n";
    echo 'Address: ' . htmlspecialchars($address) . "\n";
    echo 'Phone: ' . htmlspecialchars($phone) . "\n";
    echo 'Email: ' . htmlspecialchars($email) . "\n";
    echo 'Payment Method ID: ' . htmlspecialchars($payment_method_id) . "\n";
    echo 'Grand Total: ' . htmlspecialchars($grandTotal) . "\n";
    echo "Cart Items:\n";
    foreach ($cartItems as $item) {
        echo "- Product ID: " . htmlspecialchars($item['product_id']) . "\n";
        echo "  Packaging Option ID: " . htmlspecialchars($item['packaging_option_id']) . "\n";
        echo "  Quantity: " . htmlspecialchars($item['quantity']) . "\n";
        echo "  Price: " . htmlspecialchars($item['price']) . "\n";
    }
    echo '</pre>';
    exit;

    $db = DBConnect::getInstance();

    try {
        // Insert new order into the orders table
        $query = "INSERT INTO orders (user_id, status, total_price, shipping_address, created_at, payment_method_id) VALUES (?, 'Chờ xử lý', ?, ?, NOW(), ?)";
        $db->execute($query, [$user_id, $grandTotal, $address, $payment_method_id]);

        // Insert order details
        foreach ($cartItems as $item) {
            $query = "INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price) VALUES ((SELECT MAX(order_id) FROM orders), ?, ?, ?, ?)";
            $db->execute($query, [$item['product_id'], $item['packaging_option_id'], $item['quantity'], $item['price']]);
        }

        // Clear cart session data
        unset($_SESSION['cartItems'], $_SESSION['filteredCartItems'], $_SESSION['grandTotal']);

        exit;
    } catch (Exception $e) {
        echo '<p>Lỗi khi tạo đơn hàng: ' . htmlspecialchars($e->getMessage()) . '</p>';
        exit;
    }
} else {
    echo '<p>Yêu cầu không hợp lệ.</p>';
    exit;
}