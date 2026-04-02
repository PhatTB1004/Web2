
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Xem lại đơn hàng';
require_login('sign-in.php');
$user = current_user($conn);
$bookId = (int) ($_SESSION['checkout_book_id'] ?? 0);
$userId = (int) $user['id'];
$book = null;
$address = null;
$error = '';

if ($bookId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT b.*, a.fullname AS author_name FROM books b INNER JOIN authors a ON a.id = b.author_id WHERE b.id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);
}

$stmt = mysqli_prepare($conn, 'SELECT * FROM address WHERE user_id = ? ORDER BY is_default DESC, id DESC LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$address = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

if (!$book) {
    $error = 'Chưa chọn sách để thanh toán.';
}
if (!$address) {
    $error = 'Chưa có địa chỉ giao hàng.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $paymentMethod = $_POST['payment_method'] ?? 'Tiền mặt';
    $quantity = 1;
    $subtotal = (float) $book['sell_price'] * $quantity;
    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, 'SELECT stock_quantity FROM books WHERE id = ? FOR UPDATE');
        mysqli_stmt_bind_param($stmt, 'i', $bookId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stockRow = $result ? mysqli_fetch_assoc($result) : null;
        $stock = (int) ($stockRow['stock_quantity'] ?? 0);
        mysqli_stmt_close($stmt);
        if ($stock < $quantity) {
            throw new RuntimeException('Không đủ tồn kho.');
        }

        $stmt = mysqli_prepare($conn, 'INSERT INTO orders (user_id, date, price, status, receiver_name, receiver_phone, shipping_address, ward, district, province, payment_method) VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $status = 'completed';
        $receiverName = $address['receiver_name'];
        $receiverPhone = $address['phone'];
        $shippingAddress = $address['address_detail'];
        $ward = $address['ward'];
        $district = $address['district'];
        $province = $address['province'];
        mysqli_stmt_bind_param($stmt, 'idssssssss', $userId, $subtotal, $status, $receiverName, $receiverPhone, $shippingAddress, $ward, $district, $province, $paymentMethod);
        mysqli_stmt_execute($stmt);
        $orderId = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, 'INSERT INTO order_items (order_id, book_id, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)');
        $unitPrice = (float) $book['sell_price'];
        mysqli_stmt_bind_param($stmt, 'iidid', $orderId, $bookId, $unitPrice, $quantity, $subtotal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, 'UPDATE books SET stock_quantity = stock_quantity - ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'ii', $quantity, $bookId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        $_SESSION['last_order_id'] = $orderId;
        unset($_SESSION['checkout_book_id'], $_SESSION['checkout_address_saved']);
        header('Location: Checkout-success.php');
        exit;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        $error = 'Không thể tạo đơn hàng.';
    }
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
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title"><h4 class="card-title">Xem lại đơn đặt hàng</h4></div>
               </div>
               <div class="iq-card-body">
                  <?php if ($error): ?>
                     <div class="alert alert-danger"><?= h($error) ?></div>
                     <a href="search.php" class="btn btn-primary">Quay lại mua sắm</a>
                  <?php else: ?>
                     <div class="row">
                        <div class="col-lg-8">
                           <div class="card mb-3">
                              <div class="card-body">
                                 <h5 class="mb-3">Sản phẩm</h5>
                                 <div class="media align-items-center">
                                    <img src="<?= book_cover_url($book) ?>" class="img-fluid rounded mr-3" style="width:90px;height:auto" alt="<?= h($book['bookname']) ?>">
                                    <div class="media-body">
                                       <h6 class="mb-1"><?= h($book['bookname']) ?></h6>
                                       <p class="mb-1 text-muted"><?= h($book['author_name']) ?></p>
                                       <p class="mb-0">Số lượng: 1</p>
                                    </div>
                                    <div class="text-right"><strong><?= h(money_vn($book['sell_price'])) ?></strong></div>
                                 </div>
                              </div>
                           </div>
                           <div class="card">
                              <div class="card-body">
                                 <h5 class="mb-3">Địa chỉ giao hàng</h5>
                                 <p class="mb-1"><strong><?= h($address['receiver_name']) ?></strong> - <?= h($address['phone']) ?></p>
                                 <p class="mb-0"><?= h($address['address_detail'] . ', ' . $address['ward'] . ', ' . $address['district'] . ', ' . $address['province']) ?></p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="card mb-3">
                              <div class="card-body">
                                 <h5 class="mb-3">Thanh toán</h5>
                                 <form method="post" action="">
                                    <div class="form-group">
                                       <label>Phương thức</label>
                                       <select name="payment_method" class="form-control">
                                          <option>Tiền mặt</option>
                                          <option>Chuyển khoản</option>
                                       </select>
                                    </div>
                                    <div class="d-flex justify-content-between"><span>Tạm tính</span><span><?= h(money_vn($book['sell_price'])) ?></span></div>
                                    <div class="d-flex justify-content-between mt-2"><span>Phí vận chuyển</span><span class="text-success">Miễn phí</span></div>
                                    <hr>
                                    <div class="d-flex justify-content-between"><span class="font-weight-bold">Tổng</span><span class="font-weight-bold text-danger"><?= h(money_vn($book['sell_price'])) ?></span></div>
                                    <div class="mt-4">
                                       <button type="submit" class="btn btn-primary btn-block mb-2">Xác nhận đặt hàng</button>
                                       <a href="index.php" class="btn btn-secondary btn-block">Tiếp tục mua sắm</a>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
