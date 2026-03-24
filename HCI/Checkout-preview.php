<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

<?php include "includes/topnav.php"; ?>

<?php include "includes/database.php"; ?>

      <!-- Content -->
      <div id="content-page" class="content-page">
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title"><h4 class="card-title">Xem lại đơn đặt hàng</h4></div>
                     </div>

                     <div class="iq-card-body">
                        <!-- Thông tin đơn hàng -->
                        <div class="row">
                           <div class="col-lg-8">
                              <div class="card mb-3">
                                 <div class="card-body">
                                    <h5 class="mb-3">Sản phẩm</h5>
                                    <div class="table-responsive">
                                       <table class="table table-bordered mb-0">
                                          <thead class="thead-light">
                                             <tr>
                                                <th>#</th>
                                                <th>Sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Đơn giá</th>
                                                <th>Thành tiền</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td>1</td>
                                                <td>Economix - Các Nền Kinh Tế Vận Hành</td>
                                                <td>1</td>
                                                <td>99.900 ₫</td>
                                                <td>99.900 ₫</td>
                                             </tr>
                                             <tr>
                                                <td>2</td>
                                                <td>Người Bán Hàng Vĩ Đại Nhất Thế Giới</td>
                                                <td>1</td>
                                                <td>92.900 ₫</td>
                                                <td>92.900 ₫</td>
                                             </tr>
                                             <tr>
                                                <td>3</td>
                                                <td>Một Đời Quản Trị</td>
                                                <td>1</td>
                                                <td>136.900 ₫</td>
                                                <td>136.900 ₫</td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>

                              <!-- Địa chỉ & Thanh toán -->
                              <div class="card">
                                 <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <h6>Thông tin nhận hàng</h6>
                                          <p class="mb-1"><strong>Người nhận:</strong> User</p>
                                          <p class="mb-1"><strong>SĐT:</strong> (+84) 09******10</p>
                                          <p class="mb-0"><strong>Địa chỉ:</strong> 10/41A Âu Dương Lân, Quận 8, TP. HCM</p>
                                       </div>
                                       <div class="col-md-6">
                                          <h6>Phương thức thanh toán</h6>
                                          <p class="mb-1"><strong>Thanh toán:</strong> Tiền mặt khi nhận hàng</p>
                                          <p class="mb-0"><strong>Ghi chú:</strong> </p>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                           </div>

                           <!-- Tóm tắt bên phải -->
                           <div class="col-lg-4">
                              <div class="iq-card">
                                 <div class="iq-card-body">
                                    <h5>Chi tiết đơn hàng</h5>
                                    <div class="d-flex justify-content-between mt-2">
                                       <span>Tạm tính</span><span>329.700 ₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                       <span>Giảm giá</span><span class="text-success">-19.900 ₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                       <span>Thuế VAT</span><span>16.900 ₫</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                       <span>Phí vận chuyển</span><span class="text-success">Miễn phí</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                       <span class="font-weight-bold">Tổng</span>
                                       <span class="font-weight-bold text-danger">327.900 ₫</span>
                                    </div>

                                    <div class="mt-4">
                                       <!-- nút: quay lại hoặc xác nhận -->
                                       <a href="index.php" class="btn btn-secondary btn-block mb-2">Tiếp tục mua sắm</a>
                                       <a href="Checkout-success.php" class="btn btn-primary btn-block">Xác nhận đặt hàng</a>
                                    </div>
                                 </div>
                              </div>

                              <!-- Thông tin chính sách -->
                              <div class="iq-card mt-3">
                                 <div class="card-body">
                                    <ul class="pl-0 mb-0">
                                       <li class="mb-2">Chính sách thanh toán và bảo mật</li>
                                       <li class="mb-2">Chính sách giao hàng</li>
                                       <li>Chính sách hoàn trả</li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- end row -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

<?php include "includes/footer.php"; ?>