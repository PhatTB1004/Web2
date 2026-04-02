
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'NHASACHTV - Nhà sách trực tuyến';

$featuredBooks = [];
$sql = "SELECT b.*, a.fullname AS author_name
        FROM books b
        INNER JOIN authors a ON a.id = b.author_id
        WHERE " . public_book_status_sql() . "
        ORDER BY b.updated_at DESC, b.id DESC
        LIMIT 8";
$result = mysqli_query($conn, $sql);
if ($result) {
    $featuredBooks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
}

$categories = [];
$catSql = "SELECT c.id, c.name, c.info, COUNT(bc.book_id) AS book_count
           FROM categories c
           LEFT JOIN book_category bc ON bc.category_id = c.id
           GROUP BY c.id, c.name, c.info
           ORDER BY c.name";
$catResult = mysqli_query($conn, $catSql);
if ($catResult) {
    $categories = mysqli_fetch_all($catResult, MYSQLI_ASSOC);
    mysqli_free_result($catResult);
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-header d-flex justify-content-between align-items-center position-relative">
                  <div class="iq-header-title">
                     <h4 class="card-title mb-0">Gợi ý cho bạn</h4>
                  </div>
                  <div class="iq-card-header-toolbar d-flex align-items-center">
                     <a href="search.php" class="btn btn-sm btn-primary view-more">Xem Thêm</a>
                  </div>
               </div>
               <div class="iq-card-body">
                  <div class="row">
                     <?php foreach ($featuredBooks as $book): ?>
                        <?php $bookCats = fetch_book_categories($conn, (int) $book['id']); ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                           <div class="iq-card iq-card-block iq-card-stretch iq-card-height browse-bookcontent public-feature-card public-book-card">
                              <div class="iq-card-body p-0">
                                 <div class="d-flex align-items-center">
                                    <div class="col-6 p-0 position-relative image-overlap-shadow">
                                       <a href="<?= book_url((int) $book['id']) ?>"><img class="img-fluid rounded w-100" src="<?= book_cover_url($book) ?>" alt="<?= h($book['bookname']) ?>"></a>
                                       <div class="view-book">
                                          <a href="Checkout.php?book_id=<?= (int) $book['id'] ?>" class="btn btn-sm btn-white">Mua Ngay</a>
                                       </div>
                                    </div>
                                    <div class="col-6">
                                       <div class="mb-2">
                                          <h6 class="mb-1"><a href="<?= book_url((int) $book['id']) ?>"><?= h($book['bookname']) ?></a></h6>
                                          <p class="font-size-13 line-height mb-1"><?= h($book['author_name']) ?></p>
                                          <p class="font-size-11 text-muted mb-1"><?= h(primary_category_name($bookCats)) ?></p>
                                       </div>
                                       <div class="price d-flex align-items-center">
                                          <h6><b><?= h(money_vn($book['sell_price'])) ?></b></h6>
                                       </div>
                                       <div class="book-meta text-muted">Kho: <?= (int) $book['stock_quantity'] ?></div>
                                       <div class="iq-product-action">
                                          <a href="Checkout.php?book_id=<?= (int) $book['id'] ?>"><i class="ri-shopping-cart-2-fill text-primary"></i></a>
                                          <a href="<?= book_url((int) $book['id']) ?>" class="ml-2"><i class="ri-eye-line text-danger"></i></a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-lg-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-header d-flex justify-content-between align-items-center position-relative">
                  <div class="iq-header-title">
                     <h4 class="card-title mb-0">Danh mục nổi bật</h4>
                  </div>
                  <div class="iq-card-header-toolbar d-flex align-items-center">
                     <a href="search.php" class="btn btn-sm btn-primary view-more">Tìm theo danh mục</a>
                  </div>
               </div>
               <div class="iq-card-body">
                  <div class="row">
                     <?php foreach ($categories as $category): ?>
                        <div class="col-sm-6 col-lg-4 mb-4">
                           <div class="card h-100">
                              <div class="card-body">
                                 <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                       <h5 class="mb-2"><?= h($category['name']) ?></h5>
                                       <p class="mb-2 text-muted"><?= h($category['info']) ?></p>
                                    </div>
                                    <span class="badge badge-primary"><?= (int) $category['book_count'] ?></span>
                                 </div>
                                 <a href="search.php?category=<?= (int) $category['id'] ?>" class="btn btn-outline-primary btn-sm">Xem sách</a>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
