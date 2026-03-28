<?php
$page_title = 'Giá bán';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $bookId = (int) $_POST['book_id'];
    $profitPercent = (float) ($_POST['profit_percent'] ?? 0);
    $stmt = mysqli_prepare(db(), 'UPDATE books SET profit_percent = ?, sell_price = ?, updated_at = NOW() WHERE id = ?');
    $book = fetch_one('SELECT cost_price FROM books WHERE id = ' . $bookId);
    if ($book) {
        $sell = calc_sale_price((float) $book['cost_price'], $profitPercent);
        mysqli_stmt_bind_param($stmt, 'ddi', $profitPercent, $sell, $bookId);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    flash('success', 'Đã cập nhật tỉ lệ lợi nhuận.');
    redirect('profit.php');
}

$keyword = trim($_GET['keyword'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$where = '1=1';
if ($keyword !== '') {
    $kw = mysqli_real_escape_string(db(), $keyword);
    $where .= " AND (b.bookname LIKE '%{$kw}%' OR b.book_code LIKE '%{$kw}%')";
}
if ($categoryId > 0) {
    $where .= ' AND EXISTS (SELECT 1 FROM book_category bc WHERE bc.book_id = b.id AND bc.category_id = ' . $categoryId . ')';
}
$categories = fetch_all('SELECT * FROM categories ORDER BY name');
$rows = fetch_all("SELECT b.*, a.fullname AS author_name, (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') FROM book_category bc JOIN categories c ON c.id = bc.category_id WHERE bc.book_id = b.id) AS category_names FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where} ORDER BY b.bookname");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid">
<div class="iq-card mb-4"><div class="iq-card-header"><h4 class="card-title mb-0">Tra cứu và cập nhật giá bán</h4></div><div class="iq-card-body"><form class="row"><div class="col-md-4 form-group"><label>Tên / mã sách</label><input name="keyword" class="form-control" value="<?php echo h($keyword); ?>"></div><div class="col-md-4 form-group"><label>Phân loại</label><select name="category_id" class="form-control"><option value="0">Tất cả</option><?php foreach ($categories as $cat): ?><option value="<?php echo (int) $cat['id']; ?>" <?php echo $categoryId === (int) $cat['id'] ? 'selected' : ''; ?>><?php echo h($cat['name']); ?></option><?php endforeach; ?></select></div><div class="col-md-4 form-group align-self-end"><button class="btn btn-primary">Lọc</button></div></form></div></div>
<div class="iq-card"><div class="iq-card-body table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>#</th><th>Sách</th><th>Phân loại</th><th>Giá vốn</th><th>% LN</th><th>Giá bán</th><th>Cập nhật</th></tr></thead><tbody><?php $stt=1; foreach ($rows as $row): ?><tr><td><?php echo $stt++; ?></td><td><?php echo h($row['bookname']); ?></td><td><?php echo h($row['category_names']); ?></td><td><?php echo vn_money($row['cost_price']); ?></td><td><form method="post" class="d-flex"><input type="hidden" name="book_id" value="<?php echo (int) $row['id']; ?>"><input type="number" step="0.01" min="0" name="profit_percent" class="form-control form-control-sm mr-2" style="max-width:100px" value="<?php echo h($row['profit_percent']); ?>"><button class="btn btn-sm btn-outline-primary">Lưu</button></form></td><td><?php echo vn_money($row['sell_price']); ?></td><td><?php echo h($row['updated_at']); ?></td></tr><?php endforeach; ?></tbody></table></div></div>
</div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
