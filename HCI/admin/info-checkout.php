<?php
$page_title = 'Chi tiết đơn hàng';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$order = fetch_one('SELECT o.*, u.fullname, u.phone, u.email FROM orders o LEFT JOIN users u ON u.id = o.user_id WHERE o.id = ' . $id);
if (!$order) {
    flash('danger', 'Không tìm thấy đơn hàng.');
    redirect('checkout.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $newStatus = (string) $_POST['status'];
    $cancelReason = trim((string) ($_POST['cancel_reason'] ?? ''));

    if ($newStatus === 'cancelled' && $cancelReason === '') {
        flash('danger', 'Vui lòng nhập lý do hủy đơn.');
        redirect('info-checkout.php?id=' . $id);
    }

    if (update_order_status($id, $newStatus, $cancelReason, 'admin')) {
        flash('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
    redirect('info-checkout.php?id=' . $id);
}

$items = fetch_all('SELECT oi.*, b.bookname, b.book_code FROM order_items oi LEFT JOIN books b ON b.id = oi.book_id WHERE oi.order_id = ' . $id);
$expectedDeliveryDate = $order['expected_delivery_date'] ?? '';
$deliveryDate = $order['delivery_date'] ?? '';
$dateReceived = $order['date_received'] ?? '';
$cancelReason = $order['cancel_reason'] ?? '';
$cancelBy = $order['cancel_by'] ?? '';

$displayDeliveryDate = '';
$deliveryPrefix = '';
if (!empty($dateReceived)) {
    $displayDeliveryDate = $dateReceived;
} elseif (!empty($deliveryDate)) {
    $displayDeliveryDate = $deliveryDate;
} elseif (!empty($expectedDeliveryDate)) {
    $displayDeliveryDate = $expectedDeliveryDate;
    $deliveryPrefix = 'Dự kiến ';
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

   <div class="container-fluid">
      <div class="iq-card">
         <div class="iq-card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Chi tiết đơn hàng DH<?php echo str_pad((string) $order['id'], 3, '0', STR_PAD_LEFT); ?></h4>
            <a href="checkout.php" class="btn btn-secondary btn-sm">← Quay lại</a>
         </div>
         <div class="iq-card-body">
            <div class="row mb-4">
               <div class="col-md-6">
                  <p><strong>Khách hàng:</strong> <?php echo h($order['fullname'] ?: $order['receiver_name']); ?></p>
                  <p><strong>Email:</strong> <?php echo h($order['email']); ?></p>
                  <p><strong>Số điện thoại:</strong> <?php echo h($order['phone'] ?: $order['receiver_phone']); ?></p>
                  <p><strong>Ngày nhận:</strong> <?php echo h($displayDeliveryDate ? $deliveryPrefix . date('d/m/Y', strtotime($displayDeliveryDate)) : '—'); ?></p>
               </div>
               <div class="col-md-6">
                  <p><strong>Ngày đặt:</strong> <?php echo h($order['date']); ?></p>
                  <p><strong>Ngày giao:</strong> <?php echo h($deliveryDate ? date('d/m/Y', strtotime($deliveryDate)) : '—'); ?></p>
                  <p><strong>Địa chỉ giao hàng:</strong> <?php echo h($order['shipping_address'] . ', ' . $order['ward'] . ', ' . $order['district'] . ', ' . $order['province']); ?></p>
                  <p><strong>Trạng thái:</strong> <span class="<?php echo h(order_status_badge($order['status'])); ?>"><?php echo h(order_status_text($order['status'])); ?></span></p>
                  <?php if ($cancelReason !== ''): ?>
                     <p><strong>Lý do hủy:</strong> <?php echo h($cancelReason); ?></p>
                     <p><strong>Người hủy:</strong> <?php echo h(($cancelBy ?? '') === 'admin' ? 'Admin' : 'Người dùng'); ?></p>
                  <?php endif; ?>
               </div>
            </div>

            <div class="mb-4 p-3 border rounded bg-light">
               <form method="post" id="admin-order-status-form" class="mb-0">
                  <input type="hidden" name="id" value="<?php echo (int) $order['id']; ?>">
                  <div class="form-row align-items-end">
                     <div class="col-md-4 form-group mb-0">
                        <label>Trạng thái</label>
                        <select name="status" id="order_status_select" class="form-control">
                           <?php echo order_status_select_options($order['status']); ?>
                        </select>
                     </div>
                     <div class="col-md-6 form-group mb-0" id="cancel_reason_wrap" style="display:none;">
                        <label>Lý do hủy đơn</label>
                        <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="2" placeholder="Nhập lý do hủy nếu chọn trạng thái đã hủy"></textarea>
                     </div>
                     <div class="col-md-2 form-group mb-0">
                        <button class="btn btn-primary btn-block">Lưu</button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="table-responsive">
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th>STT</th>
                        <th>Mã sách</th>
                        <th>Tên sách</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $stt = 1; foreach ($items as $item): ?>
                     <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><?php echo h($item['book_code']); ?></td>
                        <td><?php echo h($item['bookname']); ?></td>
                        <td><?php echo (int) $item['quantity']; ?></td>
                        <td><?php echo vn_money($item['price']); ?> ₫</td>
                        <td><?php echo vn_money($item['subtotal']); ?> ₫</td>
                     </tr>
                     <?php endforeach; ?>
                     <tr class="table-primary">
                        <td colspan="5" class="text-right font-weight-bold">Tổng cộng</td>
                        <td><strong><?php echo vn_money($order['price']); ?> ₫</strong></td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
(function(){
    var select = document.getElementById('order_status_select');
    var wrap = document.getElementById('cancel_reason_wrap');
    function toggleCancelReason(){
        if (!select || !wrap) return;
        wrap.style.display = (select.value === 'cancelled') ? 'block' : 'none';
    }
    if (select) {
        select.addEventListener('change', toggleCancelReason);
        toggleCancelReason();
    }
})();
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
