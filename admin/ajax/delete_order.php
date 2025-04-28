<?php
session_start();
require_once '../../includes/DBConnect.php';
$debug = '';

$db = DBConnect::getInstance();

// Hàm trích số lượng từ chuỗi đơn vị (ví dụ "6 lon" => 6)
function getUnitValue($unitStr)
{
    preg_match('/\d+/', $unitStr, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 1;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = $_POST['order_id'] ?? null;

    if (!$order_id) {
        echo json_encode(["success" => false, "message" => "Thiếu mã đơn hàng để xoá."]);
        exit;
    }

    $pdo = $db->getConnection();
    $pdo->beginTransaction(); // ✅ Bắt đầu transaction để đảm bảo nếu lỗi sẽ rollback

    try {
        $details = $db->select('SELECT * FROM order_details WHERE order_id = ?', [$order_id]);

        if (empty($details)) {
            throw new Exception("Không tìm thấy chi tiết đơn hàng.");
        }

        // 2. Xử lý chi tiết đơn hàng để cộng lại stock
        foreach ($details as &$item) {
            $product_id = $item['product_id'];
            $packaging_option_id = $item['packaging_option_id'];
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];

            // Lấy tất cả packaging_options của sản phẩm
            $packaging = $db->select('SELECT * FROM packaging_options WHERE is_deleted = 0 AND product_id = ?', [$product_id]);

            // Lấy thông tin packaging_option đã chọn
            $packaging_option = $db->selectOne('SELECT po.*, p.name FROM packaging_options po JOIN products p ON p.product_id = po.product_id WHERE po.packaging_option_id = ?', [$packaging_option_id]);

            $quantity *= getUnitValue($packaging_option['unit_quantity']);

            // Tạo mảng lưu thông tin packaging
            $packaging_stock = [];

            foreach ($packaging as $option) {
                $packaging_stock[] = [
                    'packaging_option_id' => $option['packaging_option_id'],
                    'unit_quantity' => getUnitValue($option['unit_quantity']),
                    'stock' => (int)$option['stock'],
                    'is_change' => 0, // Mặc định chưa thay đổi
                ];
            }

            // Sắp xếp packaging_stock theo unit_quantity giảm dần
            usort($packaging_stock, function ($a, $b) {
                return $b['unit_quantity'] - $a['unit_quantity'];
            });

            $remaining_quantity = $quantity;

            // Duyệt qua các packaging_option và cộng lại stock
            foreach ($packaging_stock as &$option) {
                $unit_quantity = $option['unit_quantity'];

                if ($unit_quantity <= $remaining_quantity) {
                    $stock_used = intdiv($remaining_quantity, $unit_quantity);
                    $option['stock'] += $stock_used;
                    $remaining_quantity -= $stock_used * $unit_quantity;
                    $option['is_change'] = 1;

                    if ($remaining_quantity == 0) {
                        break;
                    }
                }
            }

            // Cập nhật lại tồn kho cho các packaging_option có thay đổi
            foreach ($packaging_stock as $option) {
                if ($option['is_change'] == 1) {
                    $db->execute(
                        "UPDATE packaging_options 
                         SET stock = ? 
                         WHERE packaging_option_id = ?",
                        [$option['stock'], $option['packaging_option_id']]
                    );
                }
            }
        }

        // 3. Xoá chi tiết đơn hàng
        $db->execute('DELETE FROM order_details WHERE order_id = ?', [$order_id]);

        // 4. Xoá đơn hàng
        $db->execute('DELETE FROM orders WHERE order_id = ?', [$order_id]);

        $pdo->commit(); // ✅ Nếu mọi thứ ok, commit lại

        echo json_encode(["success" => true, "message" => "Đã xoá đơn hàng và cập nhật lại tồn kho thành công."]);
    } catch (Exception $e) {
        $pdo->rollBack(); // ❌ Nếu có lỗi, rollback toàn bộ
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
