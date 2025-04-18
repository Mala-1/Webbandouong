<?php
$packagingOptionId = $_GET['packaging_option_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- CSS riêng -->
    <style>
        body { font-family: Arial; background: #f9f9f9; margin: 0; padding: 0; }
        .product-container { max-width: 1200px; margin: 40px auto; padding: 20px; background: #fff; display: flex; gap: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .product-image img { max-width: 350px; border-radius: 8px; }
        .product-info h1 { font-size: 28px; margin-bottom: 10px; }
        .product-info p { font-size: 16px; margin-bottom: 8px; }
        .product-price { font-size: 24px; color: #d0021b; margin: 20px 0; }
        .buy-button { background: #00bfa5; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 18px; cursor: pointer; }
        .buy-button:hover { background: #009e88; }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="product-container" id="product-detail">
    <p>Đang tải thông tin sản phẩm...</p>
</div>

<script>
    const packagingOptionId = <?= (int)$packagingOptionId ?>;

    fetch('../ajax/product_detail_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'packaging_option_id=' + packagingOptionId
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('product-detail').innerHTML = `
                <div class="product-image">
                    <img src="../assets/images/SanPham/${data.image}" alt="${data.name}">
                </div>
                <div class="product-info">
                    <h1>${data.name}</h1>
                    <p class="product-price">${data.price} đ</p>
                    <button class="buy-button">Thêm vào giỏ</button>
                </div>
            `;
        } else {
            document.getElementById('product-detail').innerHTML = '<p>Sản phẩm không tồn tại.</p>';
        }
    });
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>