
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Đơn hàng của tôi';
$user = current_user($conn);
$orders = [];
$userId = $user ? (int) $user['id'] : 0;

if ($user) {
    $stmt = mysqli_prepare($conn, 'SELECT o.*, COUNT(oi.id) AS item_count, SUM(oi.quantity) AS total_qty FROM orders o LEFT JOIN order_items oi ON oi.order_id = o.id WHERE o.user_id = ? GROUP BY o.id ORDER BY o.date DESC, o.id DESC');
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
         <div class="col-12">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Đơn hàng của tôi</h4>
                  <?php if ($user): ?>
                     <a href="profile.php" class="btn btn-outline-primary btn-sm">Hồ sơ</a>
                  <?php endif; ?>
               </div>
               <div class="iq-card-body">
                  <?php if (!$user): ?>
                     <div class="text-center py-5">
                        <h5 class="mb-3">Bạn chưa đăng nhập</h5>
                        <a href="sign-in.php" class="btn btn-primary">Đăng nhập</a>
                     </div>
                  <?php else: ?>
                     <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                           <thead class="thead-light">
                              <tr>
                                 <th>Mã đơn</th>
                                 <th>Ngày</th>
                                 <th>Số sản phẩm</th>
                                 <th>Tổng tiền</th>
                                 <th>Trạng thái</th>
                                 <th>Thanh toán</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach ($orders as $order): ?>
                                 <tr>
                                    <td>#<?= (int) $order['id'] ?></td>
                                    <td><?= h($order['date']) ?></td>
                                    <td><?= (int) ($order['total_qty'] ?? 0) ?></td>
                                    <td><?= h(money_vn($order['price'])) ?></td>
                                    <td><?= h($order['status']) ?></td>
                                    <td><?= h($order['payment_method']) ?></td>
                                 </tr>
                              <?php endforeach; ?>
                              <?php if (!$orders): ?>
                                 <tr><td colspan="6" class="text-center text-muted py-4">Chưa có đơn hàng nào.</td></tr>
                              <?php endif; ?>
                           </tbody>
                        </table>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
