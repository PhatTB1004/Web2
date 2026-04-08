<?php
$page_title = 'Tồn kho';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$from = $_GET['from'] ?? date('Y-m-01');
$to = $_GET['to'] ?? date('Y-m-d');
$keyword = trim($_GET['keyword'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$threshold = (int) ($_GET['threshold'] ?? 5);
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$categories = fetch_all('SELECT * FROM categories ORDER BY name');

$where = '1=1';
if ($keyword !== '') {
    $kw = mysqli_real_escape_string(db(), $keyword);
    $where .= " AND (b.bookname LIKE '%{$kw}%' OR b.book_code LIKE '%{$kw}%')";
}
if ($categoryId > 0) {
    $where .= ' AND EXISTS (SELECT 1 FROM book_category bc WHERE bc.book_id = b.id AND bc.category_id = ' . $categoryId . ')';
}
$fromEsc = mysqli_real_escape_string(db(), $from);
$toEsc = mysqli_real_escape_string(db(), $to);

$total = fetch_count("SELECT COUNT(*) FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$rows = fetch_all("SELECT b.*, a.fullname AS author_name, (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') FROM book_category bc JOIN categories c ON c.id = bc.category_id WHERE bc.book_id = b.id) AS category_names, (b.stock_quantity - COALESCE((SELECT SUM(ii.quantity) FROM import_items ii JOIN imports i ON i.id = ii.import_id WHERE ii.book_id = b.id AND i.status = 'completed' AND DATE(i.completed_at) > '{$toEsc}'),0) + COALESCE((SELECT SUM(oi.quantity) FROM order_items oi JOIN orders o ON o.id = oi.order_id WHERE oi.book_id = b.id AND o.status = 'delivered' AND DATE(o.`date`) > '{$toEsc}'),0)) AS stock_at_to, COALESCE((SELECT SUM(ii.quantity) FROM import_items ii JOIN imports i ON i.id = ii.import_id WHERE ii.book_id = b.id AND i.status = 'completed' AND DATE(i.completed_at) BETWEEN '{$fromEsc}' AND '{$toEsc}'),0) AS imported_qty, COALESCE((SELECT SUM(oi.quantity) FROM order_items oi JOIN orders o ON o.id = oi.order_id WHERE oi.book_id = b.id AND o.status = 'delivered' AND DATE(o.`date`) BETWEEN '{$fromEsc}' AND '{$toEsc}'),0) AS exported_qty FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where} ORDER BY b.bookname LIMIT {$offset}, {$perPage}");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="iq-card mb-4">
            <div class="iq-card-header">
                <h4 class="card-title mb-0">Tra cứu tồn kho</h4>
            </div>
            <div class="iq-card-body">
                <form class="row">
                    <div class="col-md-3 form-group"><label>Từ ngày</label><input type="date" name="from"
                            class="form-control" value="<?php echo h($from); ?>"></div>
                    <div class="col-md-3 form-group"><label>Đến ngày</label><input type="date" name="to"
                            class="form-control" value="<?php echo h($to); ?>"></div>
                    <div class="col-md-3 form-group"><label>Phân loại</label><select name="category_id"
                            class="form-control">
                            <option value="0">Tất cả</option><?php foreach ($categories as $cat): ?><option
                                value="<?php echo (int) $cat['id']; ?>"
                                <?php echo $categoryId === (int) $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo h($cat['name']); ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-3 form-group"><label>Tên / mã sách</label><input name="keyword"
                            class="form-control" value="<?php echo h($keyword); ?>"></div>
                    <div class="col-md-2 form-group"><label>Ngưỡng cảnh báo</label><input type="number" name="threshold"
                            class="form-control" value="<?php echo (int) $threshold; ?>"></div>
                    <div class="col-md-2 form-group align-self-end"><button class="btn btn-primary">Lọc</button></div>
                </form>
            </div>
        </div>
        <div class="iq-card">
            <div class="iq-card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sách</th>
                            <th>Phân loại</th>
                            <th>Tồn hiện tại</th>
                            <th>Tồn tại thời điểm</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody><?php $stt = $offset + 1; foreach ($rows as $row): $stock = (int) $row['stock_at_to']; ?><tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo h($row['bookname']); ?></td>
                            <td><?php echo h($row['category_names']); ?></td>
                            <td><?php echo (int) $row['stock_quantity']; ?></td>
                            <td><?php echo $stock; ?></td>
                            <td><?php if ($stock <= $threshold): ?><span class="badge badge-danger">Sắp hết
                                    hàng</span><?php else: ?><span class="badge badge-success">Còn
                                    hàng</span><?php endif; ?></td>
                        </tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <?php render_pagination($page, $totalPages, ['from' => $from, 'to' => $to, 'category_id' => $categoryId, 'keyword' => $keyword, 'threshold' => $threshold]); ?>
        </div>
        <div class="iq-card mt-4">
            <div class="iq-card-header">
                <h4 class="card-title mb-0">Báo cáo nhập - xuất</h4>
            </div>
            <div class="iq-card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sách</th>
                            <th>Tổng nhập</th>
                            <th>Tổng xuất</th>
                            <th>Chênh lệch</th>
                        </tr>
                    </thead>
                    <tbody><?php foreach ($rows as $row): ?><tr>
                            <td><?php echo h($row['bookname']); ?></td>
                            <td><?php echo (int) $row['imported_qty']; ?></td>
                            <td><?php echo (int) $row['exported_qty']; ?></td>
                            <td><?php echo (int) $row['imported_qty'] - (int) $row['exported_qty']; ?></td>
                        </tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>