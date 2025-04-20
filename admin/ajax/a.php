<link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
<!-- Modal chọn kiểu đóng gói -->
<div class="" id="selectPackagingModal" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn kiểu đóng gói</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- 🔍 Tìm kiếm sản phẩm -->
                <input type="text" id="searchPackaging" class="form-control" placeholder="Tìm theo tên sản phẩm...">

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Kiểu đóng gói</th>
                                <th>Đơn vị</th>
                                <th>Giá</th>
                                <th>Ảnh</th>
                                <th>Chọn</th>
                            </tr>
                        </thead>
                        <tbody id="packagingTable">
                            <!-- Dữ liệu đóng gói sẽ được load vào đây -->
                            <!-- Ví dụ:
              <tr>
                <td>Sữa tươi</td>
                <td>Lốc</td>
                <td>6 chai</td>
                <td>45.000đ</td>
                <td><img src="path.jpg" width="50"></td>
                <td><button class="btn btn-success btn-sm" onclick="selectPackaging(this)">Chọn</button></td>
              </tr>
              -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>