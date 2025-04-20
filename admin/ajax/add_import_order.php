<?php
require_once '../../includes/DBConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = DBConnect::getInstance();
    $pdo = $db->getConnection(); // dùng trực tiếp PDO

    $supplier_id = $_POST['supplier_id'] ?? null;
    session_start();
    $user_id = $_SESSION['admin_id'] ?? null;
    $import_date = $_POST['import_date'] ?? date('Y-m-d');

    $product_ids = $_POST['product_id'] ?? [];
    $packaging_ids = $_POST['packaging_option'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $prices = $_POST['price'] ?? [];

    if (!$supplier_id || !$user_id || empty($product_ids)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin phiếu nhập']);
        exit;
    }

    $total_price = 0;
    foreach ($quantities as $i => $qty) {
        $qty = (int)$qty;
        $price = floatval(str_replace(',', '', $prices[$i]));
        $total_price += $qty * $price;
    }

    try {
        $pdo->beginTransaction();

        // Lấy tỉ lệ lợi nhuận cố định (nếu có)
        $margin = 0;
        $stmtMargin = $pdo->query("SELECT margin_percent FROM profitmargin LIMIT 1");
        $marginRow = $stmtMargin->fetch();
        if ($marginRow && isset($marginRow['margin_percent'])) {
            $margin = floatval($marginRow['margin_percent']) / 100.0;
        }

        // Insert phiếu nhập
        $stmt = $pdo->prepare("INSERT INTO import_order (supplier_id, user_id, total_price, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$supplier_id, $user_id, $total_price, $import_date]);
        $import_order_id = $pdo->lastInsertId();

        // Insert chi tiết phiếu nhập + cập nhật tồn kho và giá
        foreach ($product_ids as $i => $product_id) {
            $packaging_id = $packaging_ids[$i] ?? null;
            $quantity = (int)($quantities[$i] ?? 0);
            $price = floatval(str_replace(',', '', $prices[$i] ?? 0));
            $total = $quantity * $price;

            // Insert chi tiết
            $stmt = $pdo->prepare("INSERT INTO import_order_details 
                (import_order_id, product_id, quantity, price, total_price, packaging_option_id) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$import_order_id, $product_id, $quantity, $price, $total, $packaging_id]);

            // Cập nhật tồn kho và giá cho packaging_option
            if ($packaging_id) {
                $new_price = $price + ($price * $margin);
                $stmtUpdate = $pdo->prepare("UPDATE packaging_options SET stock = stock + ?, price = ? WHERE packaging_option_id = ?");
                $stmtUpdate->execute([$quantity, round($new_price), $packaging_id]);
            }

            // Sau khi cập nhật packaging_id chính, thêm đoạn này:
            if ($packaging_id) {
                // Lấy unit_quantity của packaging_id chính (vừa nhập)
                $stmtUnit = $pdo->prepare("SELECT unit_quantity FROM packaging_options WHERE packaging_option_id = ?");
                $stmtUnit->execute([$packaging_id]);
                $unitRow = $stmtUnit->fetch();
                $mainUnit = isset($unitRow['unit_quantity']) ? (int)preg_replace('/\D/', '', $unitRow['unit_quantity']) : 1;

                // Lấy tất cả packaging_option khác của cùng product_id
                $stmtOther = $pdo->prepare("
                    SELECT packaging_option_id, unit_quantity 
                    FROM packaging_options 
                    WHERE product_id = ? AND packaging_option_id != ?
                ");
                $stmtOther->execute([$product_id, $packaging_id]);

                while ($row = $stmtOther->fetch()) {
                    $otherId = $row['packaging_option_id'];
                    $otherUnit = (int)preg_replace('/\D/', '', $row['unit_quantity']);

                    if ($mainUnit > 0 && $otherUnit > 0) {
                        $convertedStock = round($quantity * ($otherUnit / $mainUnit));

                        // Cập nhật stock (giữ nguyên price)
                        $stmtUpdateOther = $pdo->prepare("UPDATE packaging_options SET stock = ? WHERE packaging_option_id = ?");
                        $stmtUpdateOther->execute([$convertedStock, $otherId]);
                    }
                }
            }
        }

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Thêm phiếu nhập thành công']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phải gửi bằng phương thức POST']);
}
