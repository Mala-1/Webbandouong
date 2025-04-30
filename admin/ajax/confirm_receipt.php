<?php
session_start();
require_once '../../includes/DBConnect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phải gửi bằng phương thức POST']);
    exit;
}

$import_order_id = $_POST['import_order_id'] ?? null;

if (!$import_order_id) {
    echo json_encode(['success' => false, 'message' => 'Thiếu mã phiếu nhập']);
    exit;
}

function extractUnitQuantity($unitStr)
{
    preg_match('/\d+/', $unitStr, $matches);
    return isset($matches[0]) ? (int)$matches[0] : 1; // Mặc định 1 nếu không tách được
}

function ceilToNearestHundred($number)
{
    return ceil($number / 100) * 100;
}

$db = DBConnect::getInstance();
$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    // 1. Cộng stock
    $details = $db->select("SELECT packaging_option_id, quantity FROM import_order_details WHERE import_order_id = ?", [$import_order_id]);
    foreach ($details as $detail) {
        $packaging_id = $detail['packaging_option_id'];
        $quantity = (int) $detail['quantity'];
        if ($packaging_id && $quantity > 0) {
            $db->execute("UPDATE packaging_options SET stock = stock + ? WHERE packaging_option_id = ?", [$quantity, $packaging_id]);
        }
    }

    // 2. Cập nhật trạng thái phiếu nhập
    $db->execute("UPDATE import_order SET status = 'Đã xác nhận' WHERE import_order_id = ?", [$import_order_id]);

    // 3. Lấy tỉ lệ lợi nhuận
    $marginRow = $db->selectOne("SELECT margin_percent FROM profitmargin LIMIT 1");
    $margin = isset($marginRow['margin_percent']) ? (float) $marginRow['margin_percent'] : 10.0;

    // 4. Lấy danh sách sản phẩm trong đơn
    $productRows = $db->select("SELECT DISTINCT product_id FROM import_order_details WHERE import_order_id = ?", [$import_order_id]);

    foreach ($productRows as $row) {
        $product_id = $row['product_id'];

        // Lấy tất cả loại đóng gói của sản phẩm này
        $packagings = $db->select("SELECT packaging_option_id, unit_quantity, price FROM packaging_options WHERE product_id = ?", [$product_id]);

        // Lấy loại lớn nhất đã được nhập (có trong đơn)
        $base = null;
        foreach ($packagings as &$pkg) {
            $unit = extractUnitQuantity($pkg['unit_quantity']);
            $pkg['unit'] = $unit;

            $found = $db->selectOne("SELECT price FROM import_order_details WHERE import_order_id = ? AND packaging_option_id = ?", [$import_order_id, $pkg['packaging_option_id']]);
            if ($found && (!$base || $unit > $base['unit'])) {
                $base = [
                    'id' => $pkg['packaging_option_id'],
                    'unit' => $unit,
                    'price' => (float) $found['price']
                ];
            }
        }
        unset($pkg);

        // Nếu có loại lớn nhất, cập nhật giá các loại khác
        if ($base) {
            // Tính giá bán cho loại lớn nhất
            $baseSellingPrice = ceilToNearestHundred($base['price'] * (1 + $margin / 100));
            $db->execute("UPDATE packaging_options SET price = ? WHERE packaging_option_id = ?", [$baseSellingPrice, $base['id']]);

            foreach ($packagings as $pkg) {
                if ($pkg['packaging_option_id'] == $base['id']) continue;

                $adjusted = ceilToNearestHundred(($baseSellingPrice / $base['unit']) * $pkg['unit'] * 1.05);
                $db->execute("UPDATE packaging_options SET price = ? WHERE packaging_option_id = ?", [$adjusted, $pkg['packaging_option_id']]);
            }
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
