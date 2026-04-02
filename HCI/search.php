
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Tìm kiếm sách';

$q = trim($_GET['q'] ?? '');
$categoryId = (int) ($_GET['category'] ?? 0);
$authorId = (int) ($_GET['author'] ?? 0);
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 8;
$offset = ($page - 1) * $perPage;

$categories = [];
$catResult = mysqli_query($conn, 'SELECT id, name FROM categories ORDER BY name');
if ($catResult) {
    $categories = mysqli_fetch_all($catResult, MYSQLI_ASSOC);
    mysqli_free_result($catResult);
}

$authors = [];
$authorResult = mysqli_query($conn, 'SELECT id, fullname FROM authors ORDER BY fullname');
if ($authorResult) {
    $authors = mysqli_fetch_all($authorResult, MYSQLI_ASSOC);
    mysqli_free_result($authorResult);
}

$where = ["b.status IN ('active','visible')"];
if ($q !== '') {
    $safeQ = mysqli_real_escape_string($conn, $q);
    $where[] = "(b.bookname LIKE '%{$safeQ}%' OR b.book_code LIKE '%{$safeQ}%' OR a.fullname LIKE '%{$safeQ}%' OR b.info LIKE '%{$safeQ}%')";
}
if ($categoryId > 0) {
    $where[] = 'EXISTS (SELECT 1 FROM book_category bc WHERE bc.book_id = b.id AND bc.category_id = ' . $categoryId . ')';
}
if ($authorId > 0) {
    $where[] = 'b.author_id = ' . $authorId;
}

$orderBy = 'b.updated_at DESC, b.id DESC';
switch ($sort) {
    case 'price_asc':
        $orderBy = 'b.sell_price ASC, b.id DESC';
        break;
    case 'price_desc':
        $orderBy = 'b.sell_price DESC, b.id DESC';
        break;
    case 'name_asc':
        $orderBy = 'b.bookname ASC, b.id DESC';
        break;
}

$whereSql = implode(' AND ', $where);
$countSql = "SELECT COUNT(*) AS total FROM books b INNER JOIN authors a ON a.id = b.author_id WHERE {$whereSql}";
$countResult = mysqli_query($conn, $countSql);
$total = 0;
if ($countResult) {
    $total = (int) mysqli_fetch_assoc($countResult)['total'];
    mysqli_free_result($countResult);
}
$totalPages = max(1, (int) ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$listSql = "SELECT b.*, a.fullname AS author_name
            FROM books b
            INNER JOIN authors a ON a.id = b.author_id
            WHERE {$whereSql}
            ORDER BY {$orderBy}
            LIMIT {$perPage} OFFSET {$offset}";
$books = [];
$listResult = mysqli_query($conn, $listSql);
if ($listResult) {
    $books = mysqli_fetch_all($listResult, MYSQLI_ASSOC);
    mysqli_free_result($listResult);
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-3">
            <div class="iq-card">
               <div class="iq-card-header">
                  <h5 class="card-title mb-0">Bộ lọc</h5>
               </div>
               <div class="iq-card-body">
                  <form method="get" action="search.php">
                     <div class="form-group">
                        <label>Từ khóa</label>
                        <input type="text" name="q" class="form-control" value="<?= h($q) ?>" placeholder="Tên sách, tác giả, mã sách">
                     </div>
                     <div class="form-group">
                        <label>Danh mục</label>
                        <select name="category" class="form-control">
                           <option value="0">Tất cả</option>
                           <?php foreach ($categories as $category): ?>
                              <option value="<?= (int) $category['id'] ?>" <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>><?= h($category['name']) ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group">
                        <label>Tác giả</label>
                        <select name="author" class="form-control">
                           <option value="0">Tất cả</option>
                           <?php foreach ($authors as $author): ?>
                              <option value="<?= (int) $author['id'] ?>" <?= $authorId === (int) $author['id'] ? 'selected' : '' ?>><?= h($author['fullname']) ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="form-group">
                        <label>Sắp xếp</label>
                        <select name="sort" class="form-control">
                           <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                           <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                           <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                           <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Tên A-Z</option>
                        </select>
                     </div>
                     <button type="submit" class="btn btn-primary btn-block">Áp dụng</button>
                  </form>
               </div>
            </div>
         </div>
         <div class="col-lg-9">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
               <div class="iq-card-header d-flex justify-content-between align-items-center">
                  <div>
                     <h4 class="card-title mb-0">Kết quả tìm kiếm</h4>
                     <small class="text-muted"><?= $total ?> sách phù hợp</small>
                  </div>
                  <a href="search.php" class="btn btn-sm btn-outline-primary">Xóa bộ lọc</a>
               </div>
               <div class="iq-card-body">
                  <div class="row">
                     <?php foreach ($books as $book): ?>
                        <?php $bookCats = fetch_book_categories($conn, (int) $book['id']); ?>
                        <div class="col-sm-6 col-md-4 mb-4">
                           <div class="iq-card iq-card-block iq-card-stretch iq-card-height browse-bookcontent public-book-card">
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
                                       <div class="price d-flex align-items-center"><h6><b><?= h(money_vn($book['sell_price'])) ?></b></h6></div>
                                       <div class="book-meta text-muted">Còn lại: <?= (int) $book['stock_quantity'] ?></div>
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
                     <?php if (!$books): ?>
                        <div class="col-12">
                           <div class="public-empty-state text-muted">
                              Không tìm thấy sách phù hợp.
                           </div>
                        </div>
                     <?php endif; ?>
                  </div>

                  <?php if ($totalPages > 1): ?>
                     <?php
                        $baseParams = $_GET;
                        unset($baseParams['page']);
                     ?>
                     <nav aria-label="Phân trang">
                        <ul class="pagination justify-content-center mb-0">
                           <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                              <?php $baseParams['page'] = $p; ?>
                              <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                 <a class="page-link" href="search.php?<?= h(http_build_query($baseParams)) ?>"><?= $p ?></a>
                              </li>
                           <?php endfor; ?>
                        </ul>
                     </nav>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
