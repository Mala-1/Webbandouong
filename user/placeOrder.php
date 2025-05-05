<?php
session_start();
include '../includes/DBConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$note = trim($_POST['note'] ?? '');
$payment_method_id = $_POST['payment_method_id'] ?? null;
$order_code = $_POST['order_code'] ?? null;


try {
    $db = DBConnect::getInstance();

    function formatProductName($packaging_type, $unit_quantity, $product_name)
    {
        $packaging = trim($packaging_type . ' ' . $unit_quantity);
        // Loại trùng nếu packaging_type đã nằm trong unit_quantity
        if (stripos($unit_quantity, $packaging_type) !== false) {
            $packaging = $unit_quantity;
        }

        // Với unit_quantity là "1 lon", "1 chai", có thể tối giản
        if (preg_match('/^1\s+[[:alpha:]]+$/u', $unit_quantity)) {
            $packaging = $packaging_type; // chỉ in "Lon" hoặc "Chai"
        }

        return "{$packaging} {$product_name}";
    }


    $cart = $db->selectOne("SELECT cart_id FROM cart WHERE user_id = ?", [$user_id]);
    if (!$cart) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng không tồn tại']);
        exit;
    }

    $cartItems = $db->select("SELECT cd.packaging_option_id, cd.quantity, po.*, p.name as product_name
    FROM cart_details cd
    JOIN packaging_options po ON cd.packaging_option_id = po.packaging_option_id
    JOIN products p ON p.product_id = po.product_id
    WHERE cd.cart_id = ?", [$cart['cart_id']]);

    function getUnitValue($unitStr)
    {
        preg_match('/\d+/', $unitStr, $matches);
        return isset($matches[0]) ? (int)$matches[0] : 1;
    }









    $total_price = 0;
    $order_details = [];
    $all_packaging_stock = [];

    foreach ($cartItems as $item) {
        $product_id = $item['product_id'];
        if (!isset($all_packaging_stock[$product_id])) {
            // Truy xuất tất cả packaging của sản phẩm
            $options = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);

            $all_packaging_stock[$product_id] = [];

            foreach ($options as $option) {
                $all_packaging_stock[$product_id][] = [
                    'packaging_option_id' => $option['packaging_option_id'],
                    'unit_quantity' => getUnitValue($option['unit_quantity']),
                    'stock' => (int)$option['stock'],
                    'original_stock' => (int)$option['stock'],
                    'is_change' => 0
                ];
            }

            // Sắp xếp giảm dần theo đơn vị (giống add_order)
            usort($all_packaging_stock[$product_id], fn($a, $b) => $b['unit_quantity'] - $a['unit_quantity']);
        }
    }

    foreach ($cartItems as $item) {

        $product_id = $item['product_id'];
        $packaging_option_id = $item['packaging_option_id'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];
        $total_price += $quantity * $price;

        $order_details[] = [
            'product_id' => $product_id,
            'packaging_option_id' => $packaging_option_id,
            'quantity' => $quantity,
            'price' => $price
        ];

        $packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);
        $quantity *= getUnitValue($packaging_option['unit_quantity']);

        $packaging_stock = &$all_packaging_stock[$product_id];
        $remaining_quantity = $quantity;
        $check = false;

        foreach ($packaging_stock as &$option) {
            if ($option['stock'] == 0) continue;

            $unit_quantity = $option['unit_quantity'];
            if ($unit_quantity <= $remaining_quantity && $unit_quantity >= getUnitValue($packaging_option['unit_quantity'])) {
                $stock_used = intdiv($remaining_quantity, $unit_quantity);
                $stock_used = min($stock_used, $option['stock']);

                $option['stock'] -= $stock_used;
                $remaining_quantity -= $stock_used * $unit_quantity;
                $option['is_change'] = 1;

                if ($remaining_quantity == 0) {
                    $check = true;
                    break;
                }
            }
        }
        unset($option); // 🧼 Bắt buộc sau foreach có &

        if ($remaining_quantity > 0 && !$check) {
            $is_empty = true;

            usort($packaging_stock, fn($a, $b) => $a['unit_quantity'] <=> $b['unit_quantity']);

            $i = 0;
            foreach ($packaging_stock as &$option) {
                if ($option['stock'] == 0) {
                    $i++;
                    continue;
                }

                if ($option['unit_quantity'] > getUnitValue($packaging_option['unit_quantity'])) {
                    $pre_unit_quantity = $option['unit_quantity'];
                    $option['stock'] -= 1;
                    $option['is_change'] = 1;

                    for ($j = $i - 1; $j >= 0; $j--) {
                        $packaging_stock[$j]['stock'] += floor($pre_unit_quantity / $packaging_stock[$j]['unit_quantity']);
                        $pre_unit_quantity = $packaging_stock[$j]['unit_quantity'];
                        $packaging_stock[$j]['is_change'] = 1;

                        if (
                            $packaging_stock[$j]['packaging_option_id'] == $packaging_option_id &&
                            $remaining_quantity <= $packaging_stock[$j]['stock'] * $packaging_stock[$j]['unit_quantity']
                        ) {
                            $packaging_stock[$j]['stock'] -= $remaining_quantity / getUnitValue($packaging_option['unit_quantity']);
                            $remaining_quantity = 0;
                            $is_empty = false;
                            break;
                        } else {
                            $packaging_stock[$j]['stock']--;
                        }
                    }
                    break;
                }

                $i++;
            }
            unset($option); // 🧼



            // fallback tách ra lon lẻ
            if ($remaining_quantity > 0) {
                usort($packaging_stock, fn($a, $b) => $b['unit_quantity'] - $a['unit_quantity']);
                foreach ($packaging_stock as &$option) {

                    if ($option['stock'] == 0) continue;

                    if ($option['unit_quantity'] < getUnitValue($packaging_option['unit_quantity'])) {
                        $stock_used = intdiv($remaining_quantity, $option['unit_quantity']);
                        $stock_used = min($stock_used, $option['stock']);

                        $option['stock'] -= $stock_used;
                        $remaining_quantity -= $stock_used * $option['unit_quantity'];
                        $option['is_change'] = 1;

                        if ($remaining_quantity == 0) {
                            $is_empty = false;
                            break;
                        }
                    }
                }

                unset($option); // 🧼
            }

            if ($is_empty) {
                $selectedOption = array_filter($packaging_stock, function ($opt) use ($packaging_option_id) {
                    return $opt['packaging_option_id'] == $packaging_option_id;
                });
                $selectedOption = array_values($selectedOption)[0] ?? null;
                $unitLeft = $selectedOption ? $selectedOption['stock'] * $selectedOption['unit_quantity'] : 0;

                echo json_encode([
                    "success" => false,
                    "message" => "Không đủ số lượng cho sản phẩm: " .
                        formatProductName(
                            $item['packaging_type'],
                            $item['unit_quantity'],
                            $item['product_name']
                        ) . ". Chỉ còn $unitLeft sản phẩm."
                ]);
                exit;
            }
        }

        unset($packaging_stock); // Cẩn thận

    }
    unset($item); // 🧼


    // Tạo đơn hàng
    $sql = "INSERT INTO orders (user_id, status, shipping_address, note, created_at, payment_method_id)
            VALUES (?, ?, ?, ?, NOW(), ?)";
    $params = [$user_id, 'Chờ xử lý', $address, $note, $payment_method_id];
    $success = $db->execute($sql, $params);

    if (!$success) {
        echo json_encode(["success" => false, "message" => "Không thể tạo đơn hàng."]);
        exit;
    }

    $pdo = $db->getConnection();
    $order_id = $pdo->lastInsertId();

    foreach ($order_details as $detail) {
        $db->execute("INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price)
          VALUES (?, ?, ?, ?, ?)", [
            $order_id,
            $detail['product_id'],
            $detail['packaging_option_id'],
            $detail['quantity'],
            $detail['price']
        ]);
    }

    // Cập nhật stock + log
    foreach ($all_packaging_stock as $product_id => $packaging_list) {

        foreach ($packaging_list as $option) {
            if (!empty($option['is_change'])) {
                $db->execute(
                    "UPDATE packaging_options SET stock = ? WHERE packaging_option_id = ?",
                    [$option['stock'], $option['packaging_option_id']]
                );

                $delta_quantity = $option['original_stock'] - $option['stock'];
                if ($delta_quantity !== 0) {
                    // Nếu giảm tồn kho → log dương | Nếu tăng tồn kho (do tách) → log âm
                    $db->execute(
                        "INSERT INTO order_stock_log (order_id, packaging_option_id, quantity) VALUES (?, ?, ?)",
                        [$order_id, $option['packaging_option_id'], $delta_quantity]
                    );
                }
            }
        }
    }

    $db->execute("UPDATE orders SET total_price = ? WHERE order_id = ?", [$total_price, $order_id]);
















    // 3. Xoá các mục trong cart_details
    $db->execute("DELETE FROM cart_details WHERE cart_id = ?", [$cart['cart_id']]);

    // 4. Xoá session
    unset($_SESSION['filteredCartItems'], $_SESSION['grandTotal']);

    echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công!']);
} catch (Exception $e) {
    $db->rollBack();
    error_log('Lỗi đặt hàng: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi khi xử lý đơn hàng.']);
}
