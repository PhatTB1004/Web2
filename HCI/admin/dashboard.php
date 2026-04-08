<?php
$page_title = 'Bảng điều khiển';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$stats = [
    'users' => fetch_count('SELECT COUNT(*) FROM users'),
    'books' => fetch_count('SELECT COUNT(*) FROM books'),
    'orders' => fetch_count('SELECT COUNT(*) FROM orders'),
    'imports' => fetch_count('SELECT COUNT(*) FROM imports'),
    'pending' => fetch_count("SELECT COUNT(*) FROM orders WHERE status = 'pending'"),
    'revenue' => (float) (fetch_one("SELECT COALESCE(SUM(price),0) AS s FROM orders WHERE status IN ('confirmed','delivered')")['s'] ?? 0),
    'low_stock' => fetch_count('SELECT COUNT(*) FROM books WHERE stock_quantity <= 5'),
];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-6 col-lg-3">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-body">
                  <h6>Người dùng</h6>
                  <h2><?php echo number_format($stats['users']); ?></h2>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-3">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-body">
                  <h6>Sản phẩm</h6>
                  <h2><?php echo number_format($stats['books']); ?></h2>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-3">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-body">
                  <h6>Đơn hàng</h6>
                  <h2><?php echo number_format($stats['orders']); ?></h2>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lg-3">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-body">
                  <h6>Chờ xử lý</h6>
                  <h2><?php echo number_format($stats['pending']); ?></h2>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="iq-card">
               <div class="iq-card-body">
                  <h5>Doanh thu</h5>
                  <h2 class="mb-0"><?php echo vn_money($stats['revenue']); ?> ₫</h2>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="iq-card">
               <div class="iq-card-body">
                  <h5>Cảnh báo tồn kho</h5>
                  <h2 class="mb-0"><?php echo number_format($stats['low_stock']); ?> sản phẩm</h2>
               </div>
            </div>
         </div>
      </div>
      <div class="iq-card">
         <div class="iq-card-header">
            <h4 class="card-title mb-0">Đơn gần nhất</h4>
         </div>
         <div class="iq-card-body table-responsive">
            <table class="table table-striped table-bordered">
               <thead>
                  <tr>
                     <th>Mã</th>
                     <th>Khách hàng</th>
                     <th>Ngày</th>
                     <th>Tổng tiền</th>
                     <th>Trạng thái</th>
                     <th></th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach (fetch_all("SELECT o.*, u.fullname FROM orders o LEFT JOIN users u ON u.id = o.user_id ORDER BY o.`date` DESC, o.id DESC LIMIT 8") as $row): ?>
                  <tr>
                     <td><?php echo 'DH' . str_pad((string) $row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                     <td><?php echo h($row['fullname'] ?: $row['receiver_name']); ?></td>
                     <td><?php echo h($row['date']); ?></td>
                     <td><?php echo vn_money($row['price']); ?> ₫</td>
                     <td>
                        <span class="<?php echo h(order_status_badge($row['status'])); ?>">
                           <?php echo h(order_status_text($row['status'])); ?>
                        </span>
                     </td>
                     <td><a class="btn btn-sm btn-primary"
                           href="info-checkout.php?id=<?php echo (int) $row['id']; ?>">Xem</a></td>
                  </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>