<?php
$page_title = 'Đơn hàng';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = (int) $_POST['order_id'];
    $newStatus = $_POST['status'] ?? 'pending';
    if (update_order_status($orderId, $newStatus)) {
        flash('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
    redirect('checkout.php');
}

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = trim($_GET['status'] ?? '');
$ward = trim($_GET['ward'] ?? '');
$where = '1=1';
if ($from !== '') {
    $where .= " AND o.`date` >= '" . mysqli_real_escape_string(db(), $from) . "'";
}
if ($to !== '') {
    $where .= " AND o.`date` <= '" . mysqli_real_escape_string(db(), $to) . "'";
}
if ($status !== '') {
    $where .= " AND o.status = '" . mysqli_real_escape_string(db(), $status) . "'";
}
if ($ward !== '') {
    $kw = mysqli_real_escape_string(db(), $ward);
    $where .= " AND o.ward LIKE '%{$kw}%'";
}
$orders = fetch_all("SELECT o.*, u.fullname FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE {$where} ORDER BY o.ward, o.`date` DESC, o.id DESC");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid">
   <div class="iq-card mb-4"><div class="iq-card-header"><h4 class="card-title mb-0">Quản lý đơn hàng</h4></div><div class="iq-card-body"><form class="row">
      <div class="col-md-3 form-group"><label>Từ ngày</label><input type="date" name="from" class="form-control" value="<?php echo h($from); ?>"></div>
      <div class="col-md-3 form-group"><label>Đến ngày</label><input type="date" name="to" class="form-control" value="<?php echo h($to); ?>"></div>
      <div class="col-md-3 form-group"><label>Trạng thái</label><select name="status" class="form-control"><option value="">Tất cả</option><option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>pending</option><option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>confirmed</option><option value="delivered" <?php echo $status === 'delivered' ? 'selected' : ''; ?>>delivered</option><option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>cancelled</option></select></div>
      <div class="col-md-3 form-group"><label>Phường</label><input name="ward" class="form-control" value="<?php echo h($ward); ?>"></div>
      <div class="col-12"><button class="btn btn-primary">Lọc</button></div>
   </form></div></div>
   <div class="iq-card"><div class="iq-card-body table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>Mã</th><th>Khách hàng</th><th>Ngày đặt</th><th>Phường</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Cập nhật</th><th>Chi tiết</th></tr></thead><tbody><?php foreach ($orders as $row): ?><tr><td>DH<?php echo str_pad((string) $row['id'], 3, '0', STR_PAD_LEFT); ?></td><td><?php echo h($row['fullname'] ?: $row['receiver_name']); ?></td><td><?php echo h($row['date']); ?></td><td><?php echo h($row['ward']); ?></td><td><?php echo vn_money($row['price']); ?> ₫</td><td><?php echo h($row['payment_method']); ?></td><td><span class="<?php echo h(order_status_badge($row['status'])); ?>"><?php echo h($row['status']); ?></span></td><td><form method="post" class="d-flex"><input type="hidden" name="order_id" value="<?php echo (int) $row['id']; ?>"><select name="status" class="form-control form-control-sm mr-2"><option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>pending</option><option value="confirmed" <?php echo $row['status'] === 'confirmed' ? 'selected' : ''; ?>>confirmed</option><option value="delivered" <?php echo $row['status'] === 'delivered' ? 'selected' : ''; ?>>delivered</option><option value="cancelled" <?php echo $row['status'] === 'cancelled' ? 'selected' : ''; ?>>cancelled</option></select><button class="btn btn-sm btn-outline-primary">Lưu</button></form></td><td><a class="btn btn-sm btn-primary" href="info-checkout.php?id=<?php echo (int) $row['id']; ?>">Xem</a></td></tr><?php endforeach; ?></tbody></table></div></div>
</div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
