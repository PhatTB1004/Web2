<?php
$page_title = 'Sản phẩm';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if (!empty($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $importCount = fetch_count('SELECT COUNT(*) FROM import_items WHERE book_id = ' . $id);
    $orderCount = fetch_count('SELECT COUNT(*) FROM order_items WHERE book_id = ' . $id);
    $book = fetch_one('SELECT * FROM books WHERE id = ' . $id);
    if (!$book) {
        flash('danger', 'Không tìm thấy sản phẩm.');
    } elseif ($orderCount > 0) {
        mysqli_query(db(), "UPDATE books SET status = 'hidden', updated_at = NOW() WHERE id = {$id}");
        flash('warning', 'Sản phẩm đã phát sinh đơn hàng nên chỉ được ẩn để bảo toàn lịch sử.');
    } elseif ($importCount > 0) {
        mysqli_query(db(), "UPDATE books SET status = 'hidden', updated_at = NOW() WHERE id = {$id}");
        flash('warning', 'Sản phẩm đã có nhập hàng nên chỉ được ẩn trên website.');
    } else {
        if (!empty($book['image'])) {
            delete_file_if_exists($book['image']);
        }
        mysqli_query(db(), 'DELETE FROM book_category WHERE book_id = ' . $id);
        mysqli_query(db(), 'DELETE FROM books WHERE id = ' . $id);
        flash('success', 'Đã xoá sản phẩm.');
    }
    redirect('books.php');
}

if (!empty($_GET['toggle_status'])) {
    $id = (int) $_GET['toggle_status'];
    $book = fetch_one('SELECT id, status FROM books WHERE id = ' . $id);
    if ($book) {
        $newStatus = ($book['status'] === 'hidden') ? 'active' : 'hidden';
        mysqli_query(db(), "UPDATE books SET status = '" . mysqli_real_escape_string(db(), $newStatus) . "', updated_at = NOW() WHERE id = {$id}");
        flash('success', $newStatus === 'active' ? 'Đã hiển thị sản phẩm.' : 'Đã ẩn sản phẩm.');
    }
    redirect('books.php');
}

$keyword = trim($_GET['keyword'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$authorId = (int) ($_GET['author_id'] ?? 0);
$status = trim($_GET['status'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$where = '1=1';
if ($keyword !== '') {
    $kw = mysqli_real_escape_string(db(), $keyword);
    $where .= " AND (b.bookname LIKE '%{$kw}%' OR b.book_code LIKE '%{$kw}%')";
}
if ($authorId > 0) {
    $where .= ' AND b.author_id = ' . $authorId;
}
if ($status !== '') {
    $where .= " AND b.status = '" . mysqli_real_escape_string(db(), $status) . "'";
}
if ($categoryId > 0) {
    $where .= ' AND EXISTS (SELECT 1 FROM book_category bc WHERE bc.book_id = b.id AND bc.category_id = ' . mysqli_real_escape_string(db(), $categoryId) . ')';
}
$authors = fetch_all('SELECT * FROM authors ORDER BY fullname');
$categories = fetch_all('SELECT * FROM categories ORDER BY name');

$sortMap = [
    'book_code' => 'b.book_code',
    'bookname' => 'b.bookname',
    'author_name' => 'author_name',
    'category_names' => 'category_names',
    'cost_price' => 'b.cost_price',
    'profit_percent' => 'b.profit_percent',
    'sell_price' => 'b.sell_price',
    'stock_quantity' => 'b.stock_quantity',
    'status' => 'b.status',
];
[$sort, $dir] = list_sort_state($sortMap, 'bookname', 'asc');
$orderBy = list_sort_clause($sortMap, $sort, $dir, 'bookname') . ', b.id DESC';

$total = fetch_count("SELECT COUNT(*) FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$rows = fetch_all("SELECT b.*, a.fullname AS author_name, (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') FROM book_category bc JOIN categories c ON c.id = bc.category_id WHERE bc.book_id = b.id) AS category_names FROM books b LEFT JOIN authors a ON a.id = b.author_id WHERE {$where} {$orderBy} LIMIT {$offset}, {$perPage}");

$baseQuery = [
    'keyword' => $keyword,
    'category_id' => $categoryId,
    'author_id' => $authorId,
    'status' => $status,
    'sort' => $sort,
    'dir' => $dir,
];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

    <div class="container-fluid">
        <div class="iq-card mb-4">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Quản lý sản phẩm</h4><a class="btn btn-primary" href="add-book.php">Thêm sản
                    phẩm</a>
            </div>
            <div class="iq-card-body">
                <form class="row">
                    <div class="col-md-3 form-group"><label>Tên / mã sách</label><input name="keyword"
                            class="form-control" value="<?php echo h($keyword); ?>"></div>
                    <div class="col-md-2 form-group"><label>Phân loại</label><select name="category_id"
                            class="form-control">
                            <option value="0">Tất cả</option><?php foreach ($categories as $cat): ?><option
                                value="<?php echo (int) $cat['id']; ?>"
                                <?php echo $categoryId === (int) $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo h($cat['name']); ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-2 form-group"><label>Tác giả</label><select name="author_id"
                            class="form-control">
                            <option value="0">Tất cả</option><?php foreach ($authors as $a): ?><option
                                value="<?php echo (int) $a['id']; ?>"
                                <?php echo $authorId === (int) $a['id'] ? 'selected' : ''; ?>>
                                <?php echo h($a['fullname']); ?></option><?php endforeach; ?>
                        </select></div>
                    <div class="col-md-2 form-group"><label>Trạng thái</label><select name="status"
                            class="form-control">
                            <option value="">Tất cả</option>
                            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Hiển thị
                            </option>
                            <option value="hidden" <?php echo $status === 'hidden' ? 'selected' : ''; ?>>Ẩn</option>
                        </select></div>
                    <input type="hidden" name="sort" value="<?php echo h($sort); ?>">
                    <input type="hidden" name="dir" value="<?php echo h($dir); ?>">
                    <div class="col-md-3 form-group align-self-end"><button
                            class="btn btn-primary btn-block">Lọc</button></div>
                </form>
            </div>
        </div>
        <div class="iq-card">
            <div class="iq-card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo render_sortable_th('book_code', 'Mã', $baseQuery, $sort, $dir); ?></th>
                            <th>Ảnh</th>
                            <th><?php echo render_sortable_th('bookname', 'Tên sách', $baseQuery, $sort, $dir); ?></th>
                            <th><?php echo render_sortable_th('author_name', 'Tác giả', $baseQuery, $sort, $dir); ?>
                            </th>
                            <th><?php echo render_sortable_th('category_names', 'Phân loại', $baseQuery, $sort, $dir); ?>
                            </th>
                            <th><?php echo render_sortable_th('cost_price', 'Giá vốn', $baseQuery, $sort, $dir); ?></th>
                            <th><?php echo render_sortable_th('profit_percent', '% Lợi Nhuận', $baseQuery, $sort, $dir); ?>
                            </th>
                            <th><?php echo render_sortable_th('sell_price', 'Giá bán', $baseQuery, $sort, $dir); ?></th>
                            <th><?php echo render_sortable_th('stock_quantity', 'Tồn', $baseQuery, $sort, $dir); ?></th>
                            <th><?php echo render_sortable_th('status', 'Trạng thái', $baseQuery, $sort, $dir); ?></th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody><?php $stt = $offset + 1; foreach ($rows as $row): ?><tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo h($row['book_code']); ?></td>
                            <td><?php if (!empty($row['image'])): ?><img src="../<?php echo h($row['image']); ?>"
                                    style="width:50px;height:60px;object-fit:cover;border-radius:6px;"
                                    alt=""><?php endif; ?></td>
                            <td><?php echo h($row['bookname']); ?></td>
                            <td><?php echo h($row['author_name']); ?></td>
                            <td><?php echo h($row['category_names']); ?></td>
                            <td><?php echo vn_money($row['cost_price']); ?></td>
                            <td><?php echo h($row['profit_percent']); ?></td>
                            <td><?php echo vn_money($row['sell_price']); ?></td>
                            <td><?php echo (int) $row['stock_quantity']; ?></td>
                            <td>
                                <a href="books.php?toggle_status=<?php echo (int) $row['id']; ?>&<?php echo h(http_build_query(array_diff_key($baseQuery, ['page' => 1]))); ?>"
                                    class="text-decoration-none">
                                    <span class="<?php echo h(book_status_badge($row['status'])); ?>">
                                        <?php echo h(book_status_text($row['status'])); ?>
                                    </span>
                                </a>
                            </td>
                            <td><a class="btn btn-sm btn-outline-primary"
                                    href="fix-book.php?id=<?php echo (int) $row['id']; ?>">Sửa</a> <a
                                    class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá/ẩn sản phẩm?')"
                                    href="books.php?delete=<?php echo (int) $row['id']; ?>">Xoá</a></td>
                        </tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <?php render_pagination($page, $totalPages, $baseQuery); ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>