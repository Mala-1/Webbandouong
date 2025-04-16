<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        .text-success {
            color: #42c175 !important;
        }

        .text-danger {
            color: 	#f84c49 !important;
        }
    </style>
    <div class="p-4 h-100" style="background-color: #f0f0f0;">
        <!-- Tiêu đề -->
        <div>
            <h3 class="m-0">Dashboard - Quản trị</h3>
            <span class="fs-6">Quản lý thông tin bán đồ uống</span>
        </div>

        <!-- content -->
        <div>
            <!-- Thống kê -->
            <div class="row align-items-stretch">
                <div class="col-4 p-2 d-flex">
                    <div class="bg-white py-3 px-4 rounded w-100">
                        <h5>Doanh thu tháng này</h5>
                        <h3 class="text-danger fw-bold m-0 mt-3">20,000,000đ</h3>
                        <small>+10% so với tháng trước</small>
                    </div>
                </div>
                <div class="col-4 p-2 d-flex">
                    <div class="bg-white py-3 px-4 rounded w-100">
                        <h5>Sản phẩm bán chạy trong tháng</h5>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mt-1">
                                <span>Sản phẩm A</span>
                                <span>1 lon</span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span>Sản phẩm B</span>
                                <span>2 lon</span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span>Sản phẩm C</span>
                                <span>12.7k lon</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 p-2 d-flex">
                    <div class="bg-white py-3 px-4 rounded w-100">
                        <h5>Đơn hàng chưa xử lý</h5>
                        <p class="text-warning fs-4 m-0 mt-3 fw-bold">15 đơn chưa xử lý</p>
                        <small>Chờ xác nhận hoặc vận chuyển</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>