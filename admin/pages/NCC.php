<?php
$permissions = $_SESSION['permissions'] ?? [];
$canReadNCC = in_array('read', $permissions['Quản lý đơn nhập'] ?? []);
$canWriteNCC = in_array('write', $permissions['Quản lý đơn nhập'] ?? []);
$canDeleteNCC = in_array('delete', $permissions['Quản lý đơn nhập'] ?? []);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Quản lý nhà cung cấp</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background-color: #f1f1f1;
      margin: 0;
      padding: 0;
    }

    .custom-container {
      padding: 0 25px;
    }

    .custom-table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      font-size: 16px;
    }

    .custom-table th,
    .custom-table td {
      padding: 14px 18px;
      vertical-align: middle;
      border: 1px solid #dee2e6;
    }

    .custom-table th {
      background-color: #f5f5f5;
      font-weight: 600;
    }

    .action-icons i {
      cursor: pointer;
      margin: 0 6px;
      font-size: 18px;
    }

    input[type="text"] {
      font-size: 15px;
    }
  </style>
</head>

<body>
  <div class="custom-container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <?php if ($canWriteNCC): ?>
        <button class="btn btn-primary btn-add-supplier" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
          <i class="fa fa-plus"></i> THÊM
        </button>
      <?php endif; ?>
      <input style="margin: auto;" name="name" type="text" class="form-control w-25" placeholder="🔍 Tên nhà cung cấp">
    </div>
  </div>

  <div class="custom-container mt-4 bg-white rounded">
    <div style="padding: 10px;border-radius: 5px;">
      <div class="d-flex gap-2 mb-4">
        <input type="text" class="form-control" placeholder="Email" name="email">
        <input type="text" class="form-control" placeholder="Địa Chỉ" name="dia-chi">
      </div>
      <table class="custom-table text-center">
        <thead>
          <tr>
            <th>Mã nhà cung cấp</th>
            <th>Tên nhà cung cấp</th>
            <th>Email</th>
            <th>Địa chỉ</th>
            <?php if ($canWriteNCC || $canDeleteNCC): ?>
              <th>Chức năng</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody class="supplier-wrap">
          <tr>
            <td>1</td>
            <td>Công ty ABC</td>
            <td>abc@abc.com</td>
            <td>Số 10, Đường A, TPHCM</td>
            <?php if ($canWriteNCC || $canDeleteNCC): ?>
              <td class="action-icons">
                <i class="fas fa-pen text-primary"></i>
                <i class="fas fa-trash text-danger"></i>
              </td>
            <?php endif; ?>
          </tr>

        </tbody>
      </table>
    </div>

    <div class="pagination-wrap">

    </div>

  </div>
  <!-- Modal Thêm nhà cung cấp -->
  <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form id="supplierForm">
          <div class="modal-header">
            <h5 class="modal-title" id="addSupplierModalLabel">Thêm Nhà Cung Cấp</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label class="form-label">Tên nhà cung cấp</label>
              <input type="text" name="name" class="form-control" placeholder="Tên nhà cung cấp" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-12">
              <label class="form-label">Địa chỉ</label>
              <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required>
            </div>
          </div>
          <div class="modal-footer mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary">Thêm</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Modal xác nhận xoá sản phẩm -->
  <div class="modal fade" id="modalXoaNCC" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Xác nhận xoá</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Bạn có chắc chắn muốn xoá nhà cung cấp có mã <strong id="supplier-id-display"></strong> không?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
          <button type="button" class="btn btn-danger" id="btnXacNhanXoa">Xoá</button>
        </div>
      </div>
    </div>
  </div>



  <!-- Modal Sửa nhà cung cấp -->
  <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form id="editSupplierForm">
          <div class="modal-header">
            <h5 class="modal-title" id="editSupplierModalLabel">Sửa Nhà Cung Cấp</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body row g-3">
            <input type="hidden" name="supplier_id" id="editSupplierId">
            <div class="col-md-6">
              <label class="form-label">Tên nhà cung cấp</label>
              <input type="text" name="name" id="editName" class="form-control" placeholder="Tên nhà cung cấp" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="editEmail" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-12">
              <label class="form-label">Địa chỉ</label>
              <input type="text" name="address" id="editAddress" class="form-control" placeholder="Địa chỉ" required>
            </div>
          </div>
          <div class="modal-footer mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>










  <script>
    let currentFilterParams = '';

    function loadSuppliers(page = 1, params = "") {
      const supplierWrap = document.querySelector('.supplier-wrap');
      const paginationWrap = document.querySelector('.pagination-wrap');
      fetch('ajax/load_supplier.php?page=' + page + params)
        .then(res => res.text())
        .then(data => {
          const parts = data.split('SPLIT');
          supplierWrap.innerHTML = parts[0] || '';
          paginationWrap.innerHTML = parts[1] || '';
        })
    }
    loadSuppliers(1);

    document.addEventListener("pagination:change", function(e) {
      const {
        page,
        target
      } = e.detail;

      if (target === "supplierpage") {
        loadSuppliers(page, currentFilterParams);
      }

      // Add more targets as needed
    });

    // 🎯 Lắng nghe sự kiện keypress theo name thay vì placeholder
    document.querySelectorAll('input[name="name"], input[name="email"], input[name="dia-chi"]').forEach(input => {
      input.addEventListener('input', function() {
        const name = document.querySelector('input[name="name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const address = document.querySelector('input[name="dia-chi"]').value.trim();

        currentFilterParams = `&search_name=${encodeURIComponent(name)}&search_email=${encodeURIComponent(email)}&search_address=${encodeURIComponent(address)}`;
        loadSuppliers(1, currentFilterParams);
      });
    });


    let idDangXoa = null;

    document.addEventListener('click', function(e) {
      if (e.target.closest('.btn-delete-supplier')) {
        e.preventDefault();
        const btn = e.target.closest('.btn-delete-supplier');
        idDangXoa = btn.getAttribute('data-id');

        // Gán vào modal
        document.getElementById('supplier-id-display').textContent = idDangXoa;
      }
    });

    document.getElementById('btnXacNhanXoa').addEventListener('click', function() {
      if (!idDangXoa) return;

      fetch('ajax/delete_supplier.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            supplier_id: idDangXoa
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Xoá thành công → reload danh sách
            loadSuppliers(1, currentFilterParams);
          } else {
            alert('Xoá thất bại: ' + data.message);
          }
          // Đóng modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalXoaNCC'));
          modal.hide();
        });
    });

    document.getElementById("supplierForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const name = this.name.value.trim();
      const email = this.email.value.trim();
      const address = this.address.value.trim();

      fetch('ajax/add_supplier.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            name,
            email,
            address
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
            modal.hide(); // Đóng modal sau khi thêm
            loadSuppliers(1); // Reload danh sách
            this.reset(); // Reset form
            alert('Thêm nhà cung cấp thành công!');
          } else {
            alert(data.message || 'Thêm nhà cung cấp thất bại');
          }
        });
    });


    document.addEventListener('click', function(e) {
      const editBtn = e.target.closest('.btn-edit-supplier');
      if (editBtn) {
        const supplierId = editBtn.dataset.id;
        const name = editBtn.dataset.name;
        const email = editBtn.dataset.email;
        const address = editBtn.dataset.address;

        // Gán vào modal
        document.getElementById('editSupplierId').value = supplierId;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editAddress').value = address;

        const editModal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
        editModal.show();
      }
    });

    document.getElementById("editSupplierForm").addEventListener("submit", function(e) {
      e.preventDefault();

      const supplier_id = this.supplier_id.value;
      const name = this.name.value.trim();
      const email = this.email.value.trim();
      const address = this.address.value.trim();

      fetch('ajax/update_supplier.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            supplier_id,
            name,
            email,
            address
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
            modal.hide();
            loadSuppliers(1, currentFilterParams);
            alert('Cập nhật thành công!');
          } else {
            alert(data.message || 'Lỗi cập nhật');
          }
        });
    });
  </script>
</body>

</html>