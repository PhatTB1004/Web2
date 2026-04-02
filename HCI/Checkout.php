
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Thanh toán';
$user = current_user($conn);
$bookId = (int) ($_GET['book_id'] ?? ($_SESSION['checkout_book_id'] ?? 0));
if ($bookId > 0) {
    $_SESSION['checkout_book_id'] = $bookId;
}

$book = null;
if ($bookId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT b.*, a.fullname AS author_name FROM books b INNER JOIN authors a ON a.id = b.author_id WHERE b.id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid checkout-content">
      <div class="row">
         <div class="card-block show p-0 col-12">
            <div class="row align-item-center">
               <div class="col-lg-8">
                  <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between iq-border-bottom mb-0">
                        <div class="iq-header-title"><h4 class="card-title">Giỏ hàng</h4></div>
                     </div>
                     <div class="iq-card-body">
                        <?php if ($book): ?>
                           <div class="media align-items-center">
                              <img src="<?= book_cover_url($book) ?>" class="img-fluid rounded mr-3" style="width:90px;height:auto" alt="<?= h($book['bookname']) ?>">
                              <div class="media-body">
                                 <h5 class="mb-1"><?= h($book['bookname']) ?></h5>
                                 <p class="mb-1 text-muted"><?= h($book['author_name']) ?></p>
                                 <p class="mb-0">Số lượng: 1</p>
                              </div>
                              <div class="text-right">
                                 <h6 class="mb-0"><?= h(money_vn($book['sell_price'])) ?></h6>
                              </div>
                           </div>
                        <?php else: ?>
                           <div class="public-empty-state text-muted">
                              Chưa có sản phẩm nào trong giỏ. Hãy chọn một cuốn sách để thanh toán.
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title"><h4 class="card-title">Tổng đơn hàng</h4></div>
                     </div>
                     <div class="iq-card-body">
                        <div class="d-flex justify-content-between"><span>Tạm tính</span><span><?= $book ? h(money_vn($book['sell_price'])) : '0 ₫' ?></span></div>
                        <div class="d-flex justify-content-between mt-2"><span>Phí vận chuyển</span><span class="text-success">Miễn phí</span></div>
                        <hr>
                        <div class="d-flex justify-content-between"><span class="font-weight-bold">Tổng</span><span class="font-weight-bold text-danger"><?= $book ? h(money_vn($book['sell_price'])) : '0 ₫' ?></span></div>
                        <div class="mt-4">
                           <?php if ($user && $book): ?>
                              <a href="Checkout-address.php" class="btn btn-primary btn-block">Chọn địa chỉ giao hàng</a>
                           <?php elseif (!$user): ?>
                              <a href="sign-in.php?next=<?= urlencode('Checkout.php?book_id=' . $bookId) ?>" class="btn btn-primary btn-block">Đăng nhập để thanh toán</a>
                           <?php else: ?>
                              <a href="search.php" class="btn btn-primary btn-block">Chọn sách</a>
                           <?php endif; ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
