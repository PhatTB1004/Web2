
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Đặt hàng thành công';
require_login('sign-in.php');
$user = current_user($conn);
$orderId = (int) ($_SESSION['last_order_id'] ?? ($_GET['order_id'] ?? 0));
$userId = (int) $user['id'];
$order = null;
$items = [];

if ($orderId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'ii', $orderId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, 'SELECT oi.*, b.bookname, b.image FROM order_items oi INNER JOIN books b ON b.id = oi.book_id WHERE oi.order_id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
               <div class="iq-card-body text-center">
                  <div class="mb-4"><i class="ri-checkbox-circle-line" style="font-size:48px; color:green;"></i></div>
                  <h3>Đặt hàng thành công!</h3>
                  <?php if ($order): ?>
                     <p class="mb-2">Mã đơn hàng của bạn: <strong>#<?= (int) $order['id'] ?></strong></p>
                     <p class="mb-4">Chúng tôi đã ghi nhận đơn hàng và sẽ sớm xử lý.</p>
                     <div class="row justify-content-center">
                        <div class="col-md-8">
                           <div class="card mb-3">
                              <div class="card-body text-left">
                                 <h6>Thông tin đơn hàng</h6>
                                 <p class="mb-1"><strong>Số sản phẩm:</strong> <?= array_sum(array_column($items, 'quantity')) ?></p>
                                 <p class="mb-1"><strong>Tổng thanh toán:</strong> <?= h(money_vn($order['price'])) ?></p>
                                 <p class="mb-0"><strong>Phương thức:</strong> <?= h($order['payment_method']) ?></p>
                              </div>
                           </div>
                           <a href="account-order.php" class="btn btn-outline-primary btn-block mb-2">Xem đơn hàng của tôi</a>
                           <a href="index.php" class="btn btn-primary btn-block">Tiếp tục mua sắm</a>
                        </div>
                     </div>
                  <?php else: ?>
                     <p class="mb-4">Không tìm thấy thông tin đơn hàng.</p>
                     <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
