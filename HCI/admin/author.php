<?php
$page_title = 'Tác giả';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if (!empty($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $count = fetch_count('SELECT COUNT(*) FROM books WHERE author_id = ' . $id);
    if ($count > 0) {
        flash('warning', 'Tác giả đang có sản phẩm nên không thể xoá.');
    } else {
        $row = fetch_one('SELECT image FROM authors WHERE id = ' . $id);
        if ($row && !empty($row['image'])) {
            delete_file_if_exists($row['image']);
        }
        mysqli_query(db(), 'DELETE FROM authors WHERE id = ' . $id);
        flash('success', 'Đã xoá tác giả.');
    }
    redirect('author.php');
}

$keyword = trim($_GET['keyword'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$where = '1=1';
if ($keyword !== '') {
    $kw = mysqli_real_escape_string(db(), $keyword);
    $where .= " AND (a.fullname LIKE '%{$kw}%' OR a.info LIKE '%{$kw}%')";
}
$sortMap = [
    'fullname' => 'a.fullname',
    'book_count' => 'book_count',
];
[$sort, $dir] = list_sort_state($sortMap, 'fullname', 'asc');
$orderBy = list_sort_clause($sortMap, $sort, $dir, 'fullname') . ', a.id DESC';

$total = fetch_count("SELECT COUNT(*) FROM authors a WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$rows = fetch_all("SELECT a.*, (SELECT COUNT(*) FROM books WHERE author_id = a.id) AS book_count FROM authors a WHERE {$where} {$orderBy} LIMIT {$offset}, {$perPage}");
$baseQuery = ['keyword' => $keyword, 'sort' => $sort, 'dir' => $dir];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

    <div class="container-fluid">
        <div class="iq-card mb-4">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Quản lý tác giả</h4>
                <a class="btn btn-primary" href="add-author.php">Thêm tác giả</a>
            </div>
            <div class="iq-card-body">
                <form class="row" method="get">
                    <div class="col-md-8 form-group">
                        <label>Tìm kiếm</label>
                        <input name="keyword" class="form-control" value="<?php echo h($keyword); ?>" placeholder="Tên tác giả, thông tin">
                    </div>
                    <input type="hidden" name="sort" value="<?php echo h($sort); ?>">
                    <input type="hidden" name="dir" value="<?php echo h($dir); ?>">
                    <div class="col-md-4 form-group align-self-end">
                        <button class="btn btn-primary">Lọc</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="iq-card">
            <div class="iq-card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th><?php echo render_sortable_th('fullname', 'Họ tên', $baseQuery, $sort, $dir); ?></th>
                            <th>Thông tin</th>
                            <th><?php echo render_sortable_th('book_count', 'Số sản phẩm', $baseQuery, $sort, $dir); ?></th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = $offset + 1; foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php if (!empty($row['image'])): ?><img src="../<?php echo h($row['image']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" alt=""><?php endif; ?></td>
                            <td><?php echo h($row['fullname']); ?></td>
                            <td><?php echo h($row['info']); ?></td>
                            <td><?php echo (int) $row['book_count']; ?></td>
                            <td><a class="btn btn-sm btn-outline-primary" href="fix-author.php?id=<?php echo (int) $row['id']; ?>">Sửa</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá tác giả?')" href="author.php?delete=<?php echo (int) $row['id']; ?>">Xoá</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3"><?php render_pagination($page, $totalPages, $baseQuery); ?></div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
