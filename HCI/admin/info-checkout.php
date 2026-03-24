<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

    <!-- Page Content -->
    <div id="content-page" class="content-page">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="iq-card">
              <div class="iq-card-header d-flex justify-content-between align-items-center">
                <div class="iq-header-title">
                  <h4 class="card-title mb-0">Chi tiết đơn hàng DH001</h4>
                </div>
                <a href="checkout.php" class="btn btn-secondary btn-sm">← Quay lại</a>
              </div>

              <div class="iq-card-body">
                <!-- Thông tin chung -->
                <div class="mb-4">
                  <h6 class="text-primary mb-3">Thông tin đơn hàng</h6>
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Mã đơn hàng:</strong> DH001</p>
                      <p><strong>Khách hàng:</strong> Nguyễn Văn A</p>
                      <p><strong>Ngày đặt:</strong> 11/11/2025</p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Số điện thoại:</strong> 0901 234 567</p>
                      <p><strong>Địa chỉ giao hàng:</strong> 123 Đường Lê Lợi, TP.HCM</p>
                      <p><strong>Tình trạng hiện tại:</strong>
                        <span class="badge badge-info">Mới đặt</span>
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Bảng sản phẩm -->
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead class="thead-light">
                      <tr>
                        <th>STT</th>
                        <th>Tên sách</th>
                        <th>Số lượng</th>
                        <th>Đơn giá (₫)</th>
                        <th>Thành tiền (₫)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>Reading on the Worlds</td>
                        <td>2</td>
                        <td>50.000</td>
                        <td>100.000</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>The Catcher in the Rye</td>
                        <td>1</td>
                        <td>99.000</td>
                        <td>99.000</td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>Take On The Risk</td>
                        <td>1</td>
                        <td>100.000</td>
                        <td>100.000</td>
                      </tr>
                      <tr class="table-primary">
                        <td colspan="4" class="text-right font-weight-bold">Tổng cộng</td>
                        <td><strong>299.000 ₫</strong></td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- Cập nhật tình trạng -->
                <form class="mt-4">
                  <div class="form-group">
                    <label><strong>Cập nhật tình trạng đơn hàng:</strong></label>
                    <select class="form-control w-50">
                      <option>Mới đặt</option>
                      <option>Đã xử lý</option>
                      <option>Đã giao</option>
                      <option>Đã hủy</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>

              </div> <!-- end card-body -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include "includes/footer.php"; ?>