
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Tài khoản của tôi';
$user = current_user($conn);
$defaultAddress = null;
$recentOrders = [];
$userId = $user ? (int) $user['id'] : 0;

if ($user) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM address WHERE user_id = ? ORDER BY is_default DESC, id DESC LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $defaultAddress = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, 'SELECT o.*, COUNT(oi.id) AS item_count, SUM(oi.quantity) AS total_qty FROM orders o LEFT JOIN order_items oi ON oi.order_id = o.id WHERE o.user_id = ? GROUP BY o.id ORDER BY o.date DESC, o.id DESC LIMIT 3');
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $recentOrders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt);
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Tài khoản của tôi</h4>
                  <?php if ($user): ?>
                     <a href="profile-edit.php" class="btn btn-primary btn-sm">Cập nhật hồ sơ</a>
                  <?php else: ?>
                     <a href="sign-in.php" class="btn btn-primary btn-sm">Đăng nhập</a>
                  <?php endif; ?>
               </div>
               <div class="iq-card-body">
                  <?php if ($user): ?>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="card h-100">
                              <div class="card-body text-center">
                                 <img src="images/avt.jpg" class="rounded-circle mb-3" width="96" height="96" alt="avatar">
                                 <h5 class="mb-1"><?= h($user['fullname'] ?: $user['username']) ?></h5>
                                 <p class="text-muted mb-1">@<?= h($user['username']) ?></p>
                                 <p class="mb-0">Vai trò: <?= h($user['role']) ?></p>
                                 <p class="mb-0">Trạng thái: <?= h($user['status']) ?></p>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="card h-100">
                              <div class="card-body">
                                 <h6 class="mb-3">Thông tin liên hệ</h6>
                                 <p class="mb-2"><strong>Email:</strong> <?= h($user['email']) ?></p>
                                 <p class="mb-2"><strong>Điện thoại:</strong> <?= h($user['phone']) ?></p>
                                 <p class="mb-2"><strong>Họ tên:</strong> <?= h($user['fullname']) ?></p>
                                 <p class="mb-0"><strong>Địa chỉ mặc định:</strong><br><?= $defaultAddress ? h($defaultAddress['address_detail'] . ', ' . $defaultAddress['ward'] . ', ' . $defaultAddress['district'] . ', ' . $defaultAddress['province']) : 'Chưa có địa chỉ' ?></p>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="card h-100">
                              <div class="card-body">
                                 <h6 class="mb-3">Thao tác nhanh</h6>
                                 <a href="account-order.php" class="btn btn-outline-primary btn-block mb-2">Xem đơn hàng</a>
                                 <a href="profile-edit.php" class="btn btn-primary btn-block mb-2">Sửa thông tin</a>
                                 <a href="logout.php" class="btn btn-danger btn-block">Đăng xuất</a>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="mt-4">
                        <h5 class="mb-3">Đơn hàng gần đây</h5>
                        <div class="table-responsive">
                           <table class="table table-bordered mb-0">
                              <thead class="thead-light">
                                 <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày</th>
                                    <th>Số sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                       <td>#<?= (int) $order['id'] ?></td>
                                       <td><?= h($order['date']) ?></td>
                                       <td><?= (int) ($order['total_qty'] ?? 0) ?></td>
                                       <td><?= h(money_vn($order['price'])) ?></td>
                                       <td><?= h($order['status']) ?></td>
                                    </tr>
                                 <?php endforeach; ?>
                                 <?php if (!$recentOrders): ?>
                                    <tr><td colspan="5" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
                                 <?php endif; ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  <?php else: ?>
                     <div class="text-center py-5">
                        <h5 class="mb-3">Bạn chưa đăng nhập</h5>
                        <p class="text-muted mb-4">Đăng nhập để xem hồ sơ, đơn hàng và địa chỉ đã lưu.</p>
                        <a href="sign-in.php" class="btn btn-primary mr-2">Đăng nhập</a>
                        <a href="sign-up.php" class="btn btn-outline-primary">Đăng ký</a>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
