
<?php
$user = current_user($conn ?? null);
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cartCount += (int) $quantity;
    }
}
$pageTitleShort = $page_title ?? 'Trang Chủ';
?>
<!-- TOP Nav Bar -->
<div class="iq-top-navbar">
   <div class="iq-navbar-custom">
      <nav class="navbar navbar-expand-lg navbar-light p-0">
         <div class="iq-menu-bt d-flex align-items-center">
            <div class="wrapper-menu">
               <div class="main-circle"><i class="las la-bars"></i></div>
            </div>
            <div class="iq-navbar-logo d-flex justify-content-between">
               <a href="index.php" class="header-logo">
                  <img src="images/logo.png" class="img-fluid rounded-normal" alt="">
                  <div class="logo-title">
                     <span class="text-primary text-uppercase">NHASACHTV</span>
                  </div>
               </a>
            </div>
         </div>
         <div class="navbar-breadcrumb d-none d-lg-block">
            <h5 class="mb-0"><?= h($pageTitleShort) ?></h5>
         </div>
         <div class="iq-search-bar">
            <form action="search.php" method="get" class="searchbox">
               <input type="text" name="q" class="text search-input" placeholder="Tìm kiếm sách, tác giả..." value="<?= h($_GET['q'] ?? '') ?>">
               <button class="search-link" type="submit"><i class="ri-search-line"></i></button>
            </form>
         </div>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
         <i class="ri-menu-3-line"></i>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto navbar-list">
               <li class="nav-item nav-icon">
                  <a href="search.php" class="search-toggle iq-waves-effect text-gray rounded">
                     <i class="ri-search-line"></i>
                  </a>
               </li>
               <li class="nav-item nav-icon dropdown">
                  <a href="#" class="search-toggle iq-waves-effect text-gray rounded">
                     <i class="ri-notification-2-line"></i>
                     <span class="bg-primary dots"></span>
                  </a>
                  <div class="iq-sub-dropdown">
                     <div class="iq-card shadow-none m-0">
                        <div class="iq-card-body p-0">
                           <div class="bg-primary p-3">
                              <h5 class="mb-0 text-white">Thông Báo</h5>
                           </div>
                           <div class="p-3">
                              <p class="mb-0">Chưa có thông báo mới.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </li>
               <li class="nav-item nav-icon dropdown">
                  <a href="Checkout.php" class="search-toggle iq-waves-effect text-gray rounded position-relative">
                     <i class="ri-shopping-cart-2-line"></i>
                     <span class="badge badge-danger count-cart rounded-circle"><?= (int) $cartCount ?></span>
                  </a>
               </li>
               <li class="line-height pt-3">
                  <?php if ($user): ?>
                     <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
                        <img src="images/avt.jpg" class="img-fluid rounded-circle mr-3" alt="user">
                        <div class="caption">
                           <h6 class="mb-1 line-height"><?= h($user['fullname'] ?: $user['username']) ?></h6>
                           <p class="mb-0 text-primary">Đã đăng nhập</p>
                        </div>
                     </a>
                     <div class="iq-sub-dropdown iq-user-dropdown">
                        <div class="iq-card shadow-none m-0">
                           <div class="iq-card-body p-0">
                              <div class="bg-primary p-3">
                                 <h5 class="mb-0 text-white line-height">Xin Chào <?= h($user['fullname'] ?: $user['username']) ?></h5>
                              </div>
                              <a href="profile.php" class="iq-sub-card iq-bg-primary-hover">
                                 <div class="media align-items-center">
                                    <div class="rounded iq-card-icon iq-bg-primary"><i class="ri-file-user-line"></i></div>
                                    <div class="media-body ml-3"><h6 class="mb-0">Tài khoản của tôi</h6></div>
                                 </div>
                              </a>
                              <a href="account-order.php" class="iq-sub-card iq-bg-primary-hover">
                                 <div class="media align-items-center">
                                    <div class="rounded iq-card-icon iq-bg-primary"><i class="ri-account-box-line"></i></div>
                                    <div class="media-body ml-3"><h6 class="mb-0">Đơn hàng của tôi</h6></div>
                                 </div>
                              </a>
                              <a href="profile-edit.php" class="iq-sub-card iq-bg-primary-hover">
                                 <div class="media align-items-center">
                                    <div class="rounded iq-card-icon iq-bg-primary"><i class="ri-settings-3-line"></i></div>
                                    <div class="media-body ml-3"><h6 class="mb-0">Cập nhật hồ sơ</h6></div>
                                 </div>
                              </a>
                              <div class="d-inline-block w-100 text-center p-3">
                                 <a class="bg-primary iq-sign-btn" href="logout.php" role="button">Đăng xuất<i class="ri-login-box-line ml-2"></i></a>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php else: ?>
                     <a href="sign-in.php" class="search-toggle iq-waves-effect d-flex align-items-center">
                        <img src="images/avt.jpg" class="img-fluid rounded-circle mr-3" alt="user">
                        <div class="caption">
                           <h6 class="mb-1 line-height">Khách</h6>
                           <p class="mb-0 text-primary">Chưa đăng nhập</p>
                        </div>
                     </a>
                     <div class="iq-sub-dropdown iq-user-dropdown">
                        <div class="iq-card shadow-none m-0">
                           <div class="iq-card-body p-0">
                              <div class="bg-primary p-3">
                                 <h5 class="mb-0 text-white line-height">Tài khoản khách</h5>
                              </div>
                              <div class="p-3">
                                 <a href="sign-in.php" class="btn btn-primary btn-block mb-2">Đăng nhập</a>
                                 <a href="sign-up.php" class="btn btn-outline-primary btn-block">Đăng ký</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php endif; ?>
               </li>
            </ul>
         </div>
      </nav>
   </div>
</div>
<!-- TOP Nav Bar END -->
