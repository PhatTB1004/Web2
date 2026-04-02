
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$id = (int) ($_GET['id'] ?? 0);
$page_title = 'Chi tiết sách';

$book = null;
if ($id > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT b.*, a.fullname AS author_name FROM books b INNER JOIN authors a ON a.id = b.author_id WHERE b.id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);
}

if (!$book) {
    $fallback = mysqli_query($conn, "SELECT b.*, a.fullname AS author_name FROM books b INNER JOIN authors a ON a.id = b.author_id WHERE " . public_book_status_sql() . " ORDER BY b.updated_at DESC, b.id DESC LIMIT 1");
    if ($fallback) {
        $book = mysqli_fetch_assoc($fallback);
        mysqli_free_result($fallback);
    }
}

$categories = $book ? fetch_book_categories($conn, (int) $book['id']) : [];
$page_title = $book ? $book['bookname'] : 'Chi tiết sách';

$similarBooks = [];
if ($book) {
    $bookId = (int) $book['id'];
    $authorId = (int) $book['author_id'];
    $similarSql = "SELECT DISTINCT b.*, a.fullname AS author_name
                   FROM books b
                   INNER JOIN authors a ON a.id = b.author_id
                   LEFT JOIN book_category bc ON bc.book_id = b.id
                   WHERE (b.status = 'active' OR b.status = 'visible')
                     AND b.id <> {$bookId}
                     AND (b.author_id = {$authorId} OR bc.category_id IN (
                         SELECT category_id FROM book_category WHERE book_id = {$bookId}
                     ))
                   ORDER BY b.updated_at DESC, b.id DESC
                   LIMIT 8";
    $similarResult = mysqli_query($conn, $similarSql);
    if ($similarResult) {
        $similarBooks = mysqli_fetch_all($similarResult, MYSQLI_ASSOC);
        mysqli_free_result($similarResult);
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <?php if ($book): ?>
      <div class="row">
         <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Thông tin</h4>
               </div>
               <div class="iq-card-body pb-0">
                  <div class="description-contens align-items-top row">
                     <div class="col-md-6">
                        <div class="col-9">
                           <img src="<?= book_cover_url($book) ?>" class="img-fluid w-100 rounded" alt="<?= h($book['bookname']) ?>">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="iq-card-transparent iq-card-block iq-card-stretch iq-card-height">
                           <div class="iq-card-body p-0">
                              <h3 class="mb-3"><?= h($book['bookname']) ?></h3>
                              <div class="price d-flex align-items-center font-weight-500 mb-2">
                                 <span class="font-size-20 pr-2 old-price"><?= h(money_vn($book['cost_price'])) ?></span>
                                 <span class="font-size-24 text-dark"><?= h(money_vn($book['sell_price'])) ?></span>
                              </div>
                              <div class="mb-3 d-block">
                                 <span class="font-size-20 text-warning"><i class="fa fa-star mr-1"></i><i class="fa fa-star mr-1"></i><i class="fa fa-star mr-1"></i><i class="fa fa-star mr-1"></i><i class="fa fa-star"></i></span>
                              </div>
                              <div class="text-dark mb-4 pb-4 iq-border-bottom d-block"><?= nl2br(h($book['info'])) ?></div>
                              <div class="text-primary mb-2">Tác giả: <span class="text-body"><?= h($book['author_name']) ?></span></div>
                              <div class="text-primary mb-2">Mã sách: <span class="text-body"><?= h($book['book_code']) ?></span></div>
                              <div class="text-primary mb-2">Danh mục: <span class="text-body"><?= h(implode(', ', array_column($categories, 'name'))) ?></span></div>
                              <div class="text-primary mb-4">Tình trạng: <span class="text-body"><?= h($book['status']) ?></span></div>
                              <div class="mb-4 d-flex align-items-center">
                                 <a href="Checkout.php?book_id=<?= (int) $book['id'] ?>" class="btn btn-primary view-more mr-2">Thêm vào giỏ hàng</a>
                                 <a href="Checkout.php?book_id=<?= (int) $book['id'] ?>" class="btn btn-primary view-more mr-2">Mua ngay</a>
                              </div>
                              <div class="mb-3">
                                 <a href="#" class="text-body text-center"><span class="avatar-30 rounded-circle bg-primary d-inline-block mr-2"><i class="ri-heart-fill"></i></span><span>Thêm vào danh sách yêu thích</span></a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-header d-flex justify-content-between align-items-center position-relative">
                  <div class="iq-header-title"><h4 class="card-title mb-0">Sản phẩm tương tự</h4></div>
                  <div class="iq-card-header-toolbar d-flex align-items-center">
                     <a href="search.php" class="btn btn-primary">Xem thêm</a>
                  </div>
               </div>
               <div class="iq-card-body single-similar-contens">
                  <div class="row">
                     <?php foreach ($similarBooks as $similar): ?>
                        <div class="col-md-3 mb-4">
                           <div class="card h-100">
                              <img src="<?= book_cover_url($similar) ?>" class="card-img-top" alt="<?= h($similar['bookname']) ?>">
                              <div class="card-body">
                                 <h6 class="card-title"><?= h($similar['bookname']) ?></h6>
                                 <p class="card-text text-muted mb-2"><?= h($similar['author_name']) ?></p>
                                 <p class="mb-2"><b><?= h(money_vn($similar['sell_price'])) ?></b></p>
                                 <a href="<?= book_url((int) $similar['id']) ?>" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                     <?php if (!$similarBooks): ?>
                        <div class="col-12">
                           <div class="public-empty-state text-muted">Chưa có sản phẩm tương tự.</div>
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php else: ?>
         <div class="row">
            <div class="col-12">
               <div class="iq-card">
                  <div class="iq-card-body text-center">
                     <p>Không tìm thấy sách.</p>
                     <a href="search.php" class="btn btn-primary">Quay lại tìm kiếm</a>
                  </div>
               </div>
            </div>
         </div>
      <?php endif; ?>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
