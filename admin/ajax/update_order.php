<?php
session_start();
require_once '../../includes/DBConnect.php';

$db = DBConnect::getInstance();

// Hàm trích đơn vị
function getUnitValue($unitStr)
{
    preg_match('/\d+/', $unitStr, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 1;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $order_id = $data['order_id'] ?? null;
    $details = $data['details'] ?? [];

    if (!$order_id || empty($details)) {
        echo json_encode(["success" => false, "message" => "Thiếu dữ liệu cập nhật đơn hàng. " . json_encode($order_id)]);
        exit;
    }


    $pdo = $db->getConnection();
    $pdo->beginTransaction();

    try {
        $oldDetails = $db->select('SELECT * FROM order_details WHERE order_id = ?', [$order_id]);
        $oldMap = [];
        foreach ($oldDetails as $old) {
            $oldMap[$old['packaging_option_id']] = $old;
        }

        $newMap = [];
        foreach ($details as $new) {
            $newMap[$new['packaging_option_id']] = $new;
        }

        // 1. Xử lý những packaging_option bị xóa
        foreach ($oldMap as $packaging_option_id => $oldDetail) {
            if (!isset($newMap[$packaging_option_id])) {
                restoreStock($db, $oldDetail['product_id'], $packaging_option_id, $oldDetail['quantity']);
                $db->execute('DELETE FROM order_details WHERE order_detail_id = ?', [$oldDetail['order_detail_id']]);
            }
        }

        $total_price = 0;

        // 2. Xử lý update hoặc thêm chi tiết mới
        foreach ($details as $newDetail) {
            $packaging_option_id = (int)$newDetail['packaging_option_id'];
            $product_id = (int)$newDetail['product_id'];
            $quantity = (int)$newDetail['quantity'];
            $price = (float)$newDetail['price'];

            $total_price += $quantity * $price;

            if (isset($oldMap[$packaging_option_id])) {
                // Nếu đã tồn tại → cần trả stock cũ trước
                restoreStock($db, $product_id, $packaging_option_id, $oldMap[$packaging_option_id]['quantity']);

                // Sau đó mới trừ stock lại với số lượng mới
                consumeStock($db, $product_id, $packaging_option_id, $quantity);

                // Cập nhật lại order_detail
                $db->execute('UPDATE order_details SET quantity = ?, price = ? WHERE order_detail_id = ?', [
                    $quantity,
                    $price,
                    $oldMap[$packaging_option_id]['order_detail_id']
                ]);
            } else {
                // Nếu mới hoàn toàn
                consumeStock($db, $product_id, $packaging_option_id, $quantity);

                $db->execute('INSERT INTO order_details (order_id, product_id, packaging_option_id, quantity, price)
                              VALUES (?, ?, ?, ?, ?)', [
                    $order_id,
                    $product_id,
                    $packaging_option_id,
                    $quantity,
                    $price
                ]);
            }
        }

        // 3. Cập nhật tổng giá trị đơn hàng
        $db->execute('UPDATE orders 
              SET total_price = ?, status = ?, shipping_address = ?, payment_method_id = ?, note = ? 
              WHERE order_id = ?', 
              [$total_price, $data['status'], $data['shipping_address'], $data['payment_method_id'], $data['note'], $order_id]);

        $pdo->commit();
        echo json_encode(["success" => true, "message" => "Cập nhật đơn hàng thành công!"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

// 🔵 Hàm cộng trả lại stock theo nhiều cấp đóng gói
function restoreStock($db, $product_id, $packaging_option_id, $quantity)
{
    $packaging_option = $db->selectOne('SELECT * FROM packaging_options WHERE packaging_option_id = ?', [$packaging_option_id]);
    if (!$packaging_option) return;

    $quantityRestore = $quantity * getUnitValue($packaging_option['unit_quantity']); // Đổi về đơn vị nhỏ nhất

    $packagings = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);

    usort($packagings, function($a, $b) {
        return getUnitValue($b['unit_quantity']) - getUnitValue($a['unit_quantity']);
    });

    foreach ($packagings as $pack) {
        $unit = getUnitValue($pack['unit_quantity']);
        if ($unit <= 0) continue;

        $canAdd = intdiv($quantityRestore, $unit);
        if ($canAdd > 0) {
            $db->execute('UPDATE packaging_options SET stock = stock + ? WHERE packaging_option_id = ?', [$canAdd, $pack['packaging_option_id']]);
            $quantityRestore -= $canAdd * $unit;
        }

        if ($quantityRestore <= 0) break;
    }
}


// 🔵 Hàm trừ stock theo nhiều cấp đóng gói (giống add_order.php)
function consumeStock($db, $product_id, $packaging_option_id, $quantity)
{
    $packaging_option = $db->selectOne('SELECT * FROM packaging_options WHERE packaging_option_id = ?', [$packaging_option_id]);
    if (!$packaging_option) return;

    $quantityNeed = $quantity * getUnitValue($packaging_option['unit_quantity']); // Đổi về đơn vị nhỏ nhất

    $packagings = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);

    usort($packagings, function($a, $b) {
        return getUnitValue($b['unit_quantity']) - getUnitValue($a['unit_quantity']);
    });

    foreach ($packagings as $pack) {
        $unit = getUnitValue($pack['unit_quantity']);
        if ($unit <= 0 || $quantityNeed <= 0) continue;

        $currentStock = (int)$pack['stock'];
        if ($currentStock <= 0) continue;

        $canUse = min(intdiv($quantityNeed, $unit), $currentStock);
        if ($canUse > 0) {
            $db->execute('UPDATE packaging_options SET stock = stock - ? WHERE packaging_option_id = ?', [$canUse, $pack['packaging_option_id']]);
            $quantityNeed -= $canUse * $unit;
        }
    }

    // 🚨 Nếu còn thiếu vẫn chưa đủ thì lỗi
    if ($quantityNeed > 0) {
        throw new Exception("Không đủ tồn kho để xuất hàng!");
    }
}

