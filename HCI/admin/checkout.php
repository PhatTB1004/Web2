<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

    <!-- Page Content -->
    <div id="content-page" class="content-page">
      <div class="container-fluid">

        <!-- Bộ lọc tìm kiếm -->
        <div class="row">
          <div class="col-sm-12">
            <div class="iq-card mb-4">
              <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                  <h4 class="card-title">Tìm kiếm đơn hàng</h4>
                </div>
              </div>
              <div class="iq-card-body">
                <form>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label class="font-weight-bold">Từ ngày:</label>
                      <input type="date" class="form-control" value="2025-11-10">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="font-weight-bold">Đến ngày:</label>
                      <input type="date" class="form-control" value="2025-11-11">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label class="font-weight-bold">Tình trạng:</label>
                      <select class="form-control">
                        <option>Tất cả</option>
                        <option>Mới đặt</option>
                        <option>Đã xử lý</option>
                        <option>Đã giao</option>
                        <option>Đã hủy</option>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <div class="row">
          <div class="col-sm-12">
            <div class="iq-card">
              <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                  <h4 class="card-title">Danh sách đơn hàng</h4>
                </div>
              </div>
              <div class="iq-card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>STT</th>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền (₫)</th>
                        <th>Tình trạng</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>DH001</td>
                        <td>Nguyễn Văn A</td>
                        <td>2025-11-03</td>
                        <td>299.000</td>
                        <td>Mới đặt</td>
                        <td><a href="info-checkout.php" class="btn btn-primary">Xem chi tiết</a></td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>DH002</td>
                        <td>Trần Thị B</td>
                        <td>2025-11-02</td>
                        <td>179.000</td>
                        <td>Đã xử lý</td>
                        <td><a href="info-checkout.php" class="btn btn-primary">Xem chi tiết</a></td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>DH003</td>
                        <td>Lê Văn C</td>
                        <td>2025-11-01</td>
                        <td>420.000</td>
                        <td>Đã giao</td>
                        <td><a href="info-checkout.php" class="btn btn-primary">Xem chi tiết</a></td>
                      </tr>
                      <tr>
                        <td>4</td>
                        <td>DH004</td>
                        <td>Phạm Ngọc D</td>
                        <td>2025-10-30</td>
                        <td>210.000</td>
                        <td>Đã hủy</td>
                        <td><a href="info-checkout.php" class="btn btn-primary">Xem chi tiết</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

<?php include "includes/footer.php"; ?>