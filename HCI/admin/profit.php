<?php
$page_title = 'Giá bán';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_default_profit'])) {
        $defaultProfit = max(0, (float) ($_POST['default_profit_percent'] ?? 20));
        save_app_setting('default_profit_percent', $defaultProfit);
        flash('success', 'Đã lưu % Lợi Nhuận mặc định.');
        redirect('profit.php');
    }

    if (isset($_POST['book_id'])) {
        $bookId = (int) $_POST['book_id'];
        $profitPercent = max(0, (float) ($_POST['profit_percent'] ?? 0));
        $book = fetch_one('SELECT cost_price FROM books WHERE id = ' . $bookId);
        if ($book) {
            $sell = calc_sale_price((float) $book['cost_price'], $profitPercent);
            $stmt = mysqli_prepare(db(), 'UPDATE books SET profit_percent = ?, sell_price = ?, updated_at = NOW() WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'ddi', $profitPercent, $sell, $bookId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            flash('success', 'Đã cập nhật tỉ lệ lợi nhuận.');
        }
        redirect('profit.php');
    }
}

$keyword = trim($_GET['keyword'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$where = '1=1';
if ($keyword !== '') {
    $kw = mysqli_real_escape_string(db(), $keyword);
    $where .= " AND (b.bookname LIKE '%{$kw}%' OR b.book_code LIKE '%{$kw}%')";
}
if ($categoryId > 0) {
    $where .= ' AND EXISTS (SELECT 1 FROM book_category bc WHERE bc.book_id = b.id AND bc.category_id = ' . mysqli_real_escape_string(db(), $categoryId) . ')';
}
$categories = fetch_all('SELECT * FROM categories ORDER BY name');
$sortMap = [
    'bookname' => 'b.bookname',
    'cost_price' => 'b.cost_price',
    'profit_percent' => 'b.profit_percent',
    'sell_price' => 'b.sell_price',
    'updated_at' => 'b.updated_at',
];
[$sort, $dir] = list_sort_state($sortMap, 'bookname', 'asc');
$orderBy = list_sort_clause($sortMap, $sort, $dir, 'bookname') . ', b.id DESC';
$total = fetch_count("SELECT COUNT(*) FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) { $page = $totalPages; $offset = ($page - 1) * $perPage; }
$rows = fetch_all("SELECT b.*, a.fullname AS author_name, (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') FROM book_category bc JOIN categories c ON c.id = bc.category_id WHERE bc.book_id = b.id) AS category_names FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where} {$orderBy} LIMIT {$offset}, {$perPage}");
$baseQuery = ['keyword' => $keyword, 'category_id' => $categoryId, 'sort' => $sort, 'dir' => $dir];
$defaultProfit = (float) app_setting('default_profit_percent', 20);

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

<div class="container-fluid">
   <div class="iq-card mb-4">
      <div class="iq-card-header d-flex justify-content-between align-items-center">
         <h4 class="card-title mb-0">Tra cứu và cập nhật giá bán</h4>
      </div>
      <div class="iq-card-body">
         <form method="post" class="row align-items-end mb-4">
            <div class="col-md-4 form-group mb-0">
               <label>% Lợi Nhuận mặc định</label>
               <input type="number" step="0.01" min="0" name="default_profit_percent" class="form-control"
                  value="<?php echo h($defaultProfit); ?>">
            </div>
            <div class="col-md-4 form-group mb-0">
               <button class="btn btn-primary" name="save_default_profit" value="1">Lưu mặc định</button>
            </div>
         </form>
         <form class="row">
            <div class="col-md-4 form-group"><label>Tên / mã sách</label><input name="keyword" class="form-control"
                  value="<?php echo h($keyword); ?>"></div>
            <div class="col-md-4 form-group"><label>Phân loại</label><select name="category_id" class="form-control">
                  <option value="0">Tất cả</option><?php foreach ($categories as $cat): ?><option
                     value="<?php echo (int) $cat['id']; ?>"
                     <?php echo $categoryId === (int) $cat['id'] ? 'selected' : ''; ?>><?php echo h($cat['name']); ?>
                  </option><?php endforeach; ?>
               </select></div>
            <input type="hidden" name="sort" value="<?php echo h($sort); ?>"><input type="hidden" name="dir"
               value="<?php echo h($dir); ?>">
            <div class="col-md-4 form-group align-self-end"><button class="btn btn-primary">Lọc</button></div>
         </form>
      </div>
   </div>
   <div class="iq-card">
      <div class="iq-card-body table-responsive">
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>#</th>
                  <th><?php echo render_sortable_th('bookname', 'Sách', $baseQuery, $sort, $dir); ?></th>
                  <th>Phân loại</th>
                  <th><?php echo render_sortable_th('cost_price', 'Giá vốn', $baseQuery, $sort, $dir); ?></th>
                  <th><?php echo render_sortable_th('profit_percent', '% Lợi Nhuận', $baseQuery, $sort, $dir); ?></th>
                  <th><?php echo render_sortable_th('sell_price', 'Giá bán', $baseQuery, $sort, $dir); ?></th>
                  <th><?php echo render_sortable_th('updated_at', 'Cập nhật', $baseQuery, $sort, $dir); ?></th>
               </tr>
            </thead>
            <tbody><?php $stt = $offset + 1; foreach ($rows as $row): ?><tr>
                  <td><?php echo $stt++; ?></td>
                  <td><?php echo h($row['bookname']); ?></td>
                  <td><?php echo h($row['category_names']); ?></td>
                  <td><?php echo vn_money($row['cost_price']); ?></td>
                  <td>
                     <form method="post" class="d-flex"><input type="hidden" name="book_id"
                           value="<?php echo (int) $row['id']; ?>"><input type="number" step="0.01" min="0"
                           name="profit_percent" class="form-control form-control-sm mr-2" style="max-width:120px"
                           value="<?php echo h($row['profit_percent']); ?>"><button
                           class="btn btn-sm btn-outline-primary">Lưu</button></form>
                  </td>
                  <td><?php echo vn_money($row['sell_price']); ?></td>
                  <td><?php echo h($row['updated_at']); ?></td>
               </tr><?php endforeach; ?></tbody>
         </table>
      </div>
   </div>
   <div class="mt-3"><?php render_pagination($page, $totalPages, $baseQuery); ?></div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>