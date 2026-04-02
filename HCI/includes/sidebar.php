
<?php
$sidebarCategories = [];
if (isset($conn)) {
    $result = mysqli_query($conn, 'SELECT id, name FROM categories ORDER BY name');
    if ($result) {
        $sidebarCategories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
}
$user = current_user($conn ?? null);
?>
<!-- Sidebar  -->
<div class="iq-sidebar">
   <div class="iq-sidebar-logo d-flex justify-content-between">
      <a href="index.php" class="header-logo">
         <img src="images/logo.png" class="img-fluid rounded-normal" alt="">
         <div class="logo-title">
            <span class="text-primary text-uppercase">NHASACHTV</span>
         </div>
      </a>
   </div>
   <div id="sidebar-scrollbar">
      <nav class="iq-sidebar-menu">
         <ul id="iq-sidebar-toggle" class="iq-menu">
            <li class="active active-menu">
               <a href="index.php" class="iq-waves-effect"><span class="ripple rippleEffect"></span><i class="las la-home iq-arrow-left"></i><span>Trang Chủ</span></a>
            </li>
            <li>
               <a href="#ui-elements" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="lab la-elementor iq-arrow-left"></i><span>Danh mục sản phẩm</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
               <ul id="ui-elements" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                  <?php foreach ($sidebarCategories as $category): ?>
                     <li class="elements">
                        <a href="search.php?category=<?= (int) $category['id'] ?>" class="iq-waves-effect collapsed"><i class="ri-play-circle-line"></i><span><?= h($category['name']) ?></span></a>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </li>
            <?php if ($user): ?>
               <li><a href="profile.php"><i class="ri-user-line"></i>Tài khoản của tôi</a></li>
               <li><a href="account-order.php"><i class="ri-file-list-3-line"></i>Đơn hàng của tôi</a></li>
               <li><a href="logout.php"><i class="ri-logout-box-r-line"></i>Đăng xuất</a></li>
            <?php else: ?>
               <li><a href="sign-in.php"><i class="ri-login-box-line"></i>Đăng nhập</a></li>
               <li><a href="sign-up.php"><i class="ri-user-add-line"></i>Đăng ký</a></li>
            <?php endif; ?>
         </ul>
      </nav>
   </div>
</div>
