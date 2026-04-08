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
    if (update_order_status($id, $_POST['status'])) {
        flash('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
    redirect('info-checkout.php?id=' . $id);
}

$items = fetch_all('SELECT oi.*, b.bookname, b.book_code FROM order_items oi LEFT JOIN books b ON b.id = oi.book_id WHERE oi.order_id = ' . $id);

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="iq-card">
         <div class="iq-card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Chi tiết đơn hàng
               DH<?php echo str_pad((string) $order['id'], 3, '0', STR_PAD_LEFT); ?></h4>
            <a href="checkout.php" class="btn btn-secondary btn-sm">← Quay lại</a>
         </div>
         <div class="iq-card-body">
            <div class="row mb-4">
               <div class="col-md-6">
                  <p><strong>Khách hàng:</strong> <?php echo h($order['fullname'] ?: $order['receiver_name']); ?></p>
                  <p><strong>Email:</strong> <?php echo h($order['email']); ?></p>
                  <p><strong>Số điện thoại:</strong> <?php echo h($order['phone'] ?: $order['receiver_phone']); ?></p>
               </div>
               <div class="col-md-6">
                  <p><strong>Ngày đặt:</strong> <?php echo h($order['date']); ?></p>
                  <p><strong>Địa chỉ giao hàng:</strong>
                     <?php echo h($order['shipping_address'] . ', ' . $order['ward'] . ', ' . $order['district'] . ', ' . $order['province']); ?>
                  </p>
                  <p><strong>Trạng thái:</strong>
                     <span class="<?php echo h(order_status_badge($order['status'])); ?>">
                        <?php echo h(order_status_text($order['status'])); ?>
                     </span>
                  </p>
               </div>
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
            <form method="post" class="mt-4">
               <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
               <div class="form-group">
                  <label><strong>Cập nhật tình trạng đơn hàng:</strong></label>
                  <select name="status" class="form-control w-50">
                     <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>
                        Chờ xử lý
                     </option>
                     <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>
                        Đã xác nhận
                     </option>
                     <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>
                        Đã giao
                     </option>
                     <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>
                        Đã hủy
                     </option>
                  </select>
               </div>
               <button class="btn btn-primary">Lưu thay đổi</button>
            </form>
         </div>
      </div>
   </div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>