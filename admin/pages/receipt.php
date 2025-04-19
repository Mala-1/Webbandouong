<?php
// Kết nối CSDL
$conn = new mysqli('localhost', 'root', '', 'banhang');
$conn->set_charset('utf8');

// Lấy danh sách sản phẩm
$sql = "SELECT id, ten_san_pham, don_gia FROM sanpham";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Phiếu Nhập Hàng</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-4">Phiếu Nhập Hàng</h1>

    <!-- Thông tin chung -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div>
        <label class="block font-semibold mb-1">Mã phiếu:</label>
        <input type="text" class="w-full border rounded px-3 py-2" placeholder="PN001" />
      </div>
      <div>
        <label class="block font-semibold mb-1">Ngày nhập:</label>
        <input type="date" class="w-full border rounded px-3 py-2" />
      </div>
      <div>
        <label class="block font-semibold mb-1">Nhân viên nhập:</label>
        <input type="text" class="w-full border rounded px-3 py-2" placeholder="Nguyễn Văn A" />
      </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <table class="w-full border text-left mb-4">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2">Tên sản phẩm</th>
          <th class="p-2">Số lượng</th>
          <th class="p-2">Đơn giá</th>
          <th class="p-2">Thành tiền</th>
          <th class="p-2">Thao tác</th>
        </tr>
      </thead>
      <tbody id="product-list">
        <tr>
          <td class="p-2">
            <select class="product-select w-full border rounded px-2 py-1"></select>
          </td>
          <td class="p-2">
            <input type="number" class="quantity w-full border rounded px-2 py-1" value="1" min="1" />
          </td>
          <td class="p-2">
            <input type="number" class="price w-full border rounded px-2 py-1" value="0" />
          </td>
          <td class="p-2">
            <span class="total">0</span>
          </td>
          <td class="p-2">
            <button onclick="removeRow(this)" class="bg-red-500 text-white px-2 py-1 rounded">Xóa</button>
          </td>
        </tr>
      </tbody>
    </table>

    <button onclick="addRow()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">+ Thêm sản phẩm</button>

    <!-- Tổng cộng -->
    <div class="text-right font-semibold text-lg">
      Tổng tiền: <span id="grand-total">0</span> VND
    </div>

    <!-- Nút lưu -->
    <div class="mt-6 text-right">
      <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Lưu Phiếu Nhập</button>
    </div>
  </div>

  <script>
    const productData = <?php echo json_encode($products); ?>;

    function populateAllSelects() {
      document.querySelectorAll('.product-select').forEach(select => {
        // Tránh nhân bản option nếu đã có
        if (select.options.length > 0) return;

        select.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
        productData.forEach(p => {
          const option = document.createElement('option');
          option.value = p.id;
          option.textContent = p.ten_san_pham;
          option.dataset.price = p.don_gia;
          select.appendChild(option);
        });

        select.onchange = function () {
          const selected = this.options[this.selectedIndex];
          const price = selected.dataset.price || 0;
          const row = this.closest('tr');
          row.querySelector('.price').value = price;
          calculateTotal();
        };
      });
    }

    function addRow() {
      const row = `
        <tr>
          <td class="p-2">
            <select class="product-select w-full border rounded px-2 py-1"></select>
          </td>
          <td class="p-2">
            <input type="number" class="quantity w-full border rounded px-2 py-1" value="1" min="1" />
          </td>
          <td class="p-2">
            <input type="number" class="price w-full border rounded px-2 py-1" value="0" />
          </td>
          <td class="p-2">
            <span class="total">0</span>
          </td>
          <td class="p-2">
            <button onclick="removeRow(this)" class="bg-red-500 text-white px-2 py-1 rounded">Xóa</button>
          </td>
        </tr>`;
      document.getElementById('product-list').insertAdjacentHTML('beforeend', row);
      updateEvents();
      populateAllSelects();
    }

    function removeRow(button) {
      button.closest('tr').remove();
      calculateTotal();
    }

    function updateEvents() {
      document.querySelectorAll('.quantity, .price').forEach(input => {
        input.oninput = calculateTotal;
      });
    }

    function calculateTotal() {
      let grandTotal = 0;
      document.querySelectorAll('#product-list tr').forEach(row => {
        const qty = parseInt(row.querySelector('.quantity')?.value) || 0;
        const price = parseInt(row.querySelector('.price')?.value) || 0;
        const total = qty * price;
        row.querySelector('.total').innerText = total.toLocaleString();
        grandTotal += total;
      });
      document.getElementById('grand-total').innerText = grandTotal.toLocaleString();
    }

    // Gọi khi trang load
    updateEvents();
    populateAllSelects();
    calculateTotal();
  </script>
</body>
</html>