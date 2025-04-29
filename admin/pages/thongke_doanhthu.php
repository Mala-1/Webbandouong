<?php require_once '../includes/DBConnect.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="px-2">
    <h2 class="mb-4">Thống kê Doanh Thu</h2>

    <form id="filterForm" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Từ ngày</label>
            <input type="date" name="from" id="fromDate" class="form-control" value="<?= date('Y-m-01') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Đến ngày</label>
            <input type="date" name="to" id="toDate" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">LÀM MỚI</button>
        </div>
        <input type="hidden" id="limitProduct" value="5">
        <input type="hidden" id="limitCategory" value="5">
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="border rounded p-3 bg-light">
                <h6 class="mb-1">Tổng doanh thu</h6>
                <h4 class="text-success" id="revenueBox">0đ</h4>
            </div>
        </div>
    </div>

    <div class="row g-4" id="statContent">
        <!-- Biểu đồ và bảng sẽ được chèn vào đây -->
    </div>
</div>

<script>
let productChart, categoryChart;

function renderChart(canvasId, type, labels, data, colors = null) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    return new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [{
                label: 'Số lượng bán',
                data: data,
                backgroundColor: colors || ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                borderColor: '#000',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: type === 'bar' ? { y: { beginAtZero: true } } : {}
        }
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

function loadStats() {
    console.log('debug')
    const from = $('#fromDate').val();
    const to = $('#toDate').val();
    const limitProduct = $('#limitProduct').val();
    const limitCategory = $('#limitCategory').val();

    $.getJSON('ajax/load_thongke.php', {
        from,
        to,
        limit_product: limitProduct,
        limit_category: limitCategory
    }, function(res) {
        console.log(res)
        $('#revenueBox').text(formatCurrency(res.total_revenue || 0));
        console.log(res.total_revenue)
        let html = `
        <div class="col-md-6 pe-3">
            <h5 class="d-flex align-items-center">
                <span class="me-2">Sản phẩm bán chạy</span>
                <input type="number" id="limitProductInline" class="form-control form-control-sm ms-2" style="width: 80px;" value="${limitProduct}" min="1">
            </h5>
            <table class="table table-bordered">
                <thead><tr><th>#</th><th>Tên</th><th>Số lượng</th></tr></thead>
                <tbody>
                    ${res.products.map((p, i) => `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${p.name}</td>
                            <td>${p.total_quantity}</td>
                        </tr>`).join('')}
                </tbody>
            </table>
            <canvas id="productChart" class="p-4"></canvas>
        </div>

        <div class="col-md-6 ps-3">
            <h5 class="d-flex align-items-center">
                <span class="me-2">Thể loại bán chạy</span>
                <input type="number" id="limitCategoryInline" class="form-control form-control-sm ms-2" style="width: 80px;" value="${limitCategory}" min="1">
            </h5>
            <table class="table table-bordered">
                <thead><tr><th>#</th><th>Thể loại</th><th>Số lượng</th></tr></thead>
                <tbody>
                    ${res.categories.map((c, i) => `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${c.name}</td>
                            <td>${c.total_quantity}</td>
                        </tr>`).join('')}
                </tbody>
            </table>
            <canvas id="categoryChart" class="p-4"></canvas>
        </div>`;

        $('#statContent').html(html);

        if (productChart) productChart.destroy();
        if (categoryChart) categoryChart.destroy();

        productChart = renderChart('productChart', 'bar',
            res.products.map(p => p.name),
            res.products.map(p => p.total_quantity)
        );

        categoryChart = renderChart('categoryChart', 'doughnut',
            res.categories.map(c => c.name),
            res.categories.map(c => c.total_quantity)
        );
    });
}

$(document).ready(function () {
    loadStats();

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadStats();
    });

    $(document).on('change', '#limitProductInline', function () {
        $('#limitProduct').val($(this).val());
        loadStats();
    });

    $(document).on('change', '#limitCategoryInline', function () {
        $('#limitCategory').val($(this).val());
        loadStats();
    });
});
</script>
