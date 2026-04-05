<!-- Wrapper Start -->
<div class="wrapper">
   <div class="iq-sidebar">
      <div class="iq-sidebar-logo d-flex justify-content-between align-items-center">
         <a href="dashboard.php" class="header-logo d-flex align-items-center">
            <img src="../images/logo.png" class="img-fluid rounded-normal" alt="Logo">
            <div class="logo-title ml-2">
               <span class="text-primary text-uppercase">NhasachTV</span>
            </div>
         </a>
      </div>
      <div class="px-3 py-3 border-bottom">
         <div class="text-white-50 small">Tài khoản quản trị</div>
         <div class="font-weight-bold text-white">
            <?php echo h($currentAdmin['full_name'] ?? $currentAdmin['fullname'] ?? 'Quản trị'); ?></div>
         <div class="small text-light"><?php echo h($currentAdmin['email'] ?? ''); ?></div>
      </div>
      <div id="sidebar-scrollbar">
         <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
               <li><a href="dashboard.php"><i class="las la-home iq-arrow-left"></i>Bảng điều khiển</a></li>
               <li><a href="user.php"><i class="ri-user-line"></i>Người dùng</a></li>
               <li><a href="category.php"><i class="ri-price-tag-3-line"></i>Phân loại</a></li>
               <li><a href="author.php"><i class="ri-user-star-line"></i>Tác giả</a></li>
               <li><a href="books.php"><i class="ri-book-line"></i>Sản phẩm</a></li>
               <li><a href="import.php"><i class="ri-download-2-line"></i>Nhập hàng</a></li>
               <li><a href="profit.php"><i class="ri-money-dollar-circle-line"></i>Giá bán</a></li>
               <li><a href="checkout.php"><i class="ri-shopping-cart-2-line"></i>Đơn hàng</a></li>
               <li><a href="inventory.php"><i class="ri-store-2-line"></i>Tồn kho</a></li>
               <li><a href="logout.php"><i class="ri-logout-box-line"></i>Đăng xuất</a></li>
            </ul>
         </nav>
      </div>
   </div>
</div>