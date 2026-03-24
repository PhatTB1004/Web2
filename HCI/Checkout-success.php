<?php include "includes/header.php"; ?>

<?php include "includes/database.php"; ?>

   <div id="content-page" class="content-page">
      <div class="container-fluid">
         <div class="row">
            <div class="col-20">
               <div class="iq-card">
                  <div class="iq-card-body text-center">
                     <div class="mb-4">
                        <i class="ri-checkbox-circle-line" style="font-size:48px; color:green;"></i>
                     </div>
                     <h3>Đặt hàng thành công!</h3>
                     <p class="mb-2">Mã đơn hàng của bạn: <strong>#DH20251106001</strong></p>
                     <p class="mb-4">Chúng tôi đã gửi email xác nhận đến địa chỉ của bạn. Bạn có thể xem chi tiết đơn
                        hàng trong "Đơn hàng của tôi".</p>

                     <div class="row justify-content-center">
                        <div class="col-md-6">
                           <div class="card mb-3">
                              <div class="card-body text-left">
                                 <h6>Thông tin đơn hàng</h6>
                                 <p class="mb-1"><strong>Số sản phẩm:</strong> 3</p>
                                 <p class="mb-1"><strong>Tổng thanh toán:</strong> 327.900 ₫</p>
                                 <p class="mb-0"><strong>Phương thức:</strong>Tiền mặt</p>
                              </div>
                           </div>

                           <a href="account-order.php" class="btn btn-outline-primary btn-block mb-2">Xem đơn hàng
                              của tôi</a>
                           <a href="index.php" class="btn btn-primary btn-block">Tiếp tục mua sắm</a>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>

<?php include "includes/footer.php"; ?>