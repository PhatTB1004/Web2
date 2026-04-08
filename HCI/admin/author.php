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

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$total = fetch_count('SELECT COUNT(*) FROM authors');
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$rows = fetch_all('SELECT a.*, (SELECT COUNT(*) FROM books WHERE author_id = a.id) AS book_count FROM authors a ORDER BY a.id DESC LIMIT ' . $offset . ', ' . $perPage);

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Quản lý tác giả</h4>
                <a class="btn btn-primary" href="add-author.php">Thêm tác giả</a>
            </div>
            <div class="iq-card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ảnh</th>
                            <th>Họ tên</th>
                            <th>Thông tin</th>
                            <th>Số sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody><?php $stt = $offset + 1; foreach ($rows as $row): ?><tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php if (!empty($row['image'])): ?><img src="../<?php echo h($row['image']); ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;"
                                    alt=""><?php endif; ?></td>
                            <td><?php echo h($row['fullname']); ?></td>
                            <td><?php echo h($row['info']); ?></td>
                            <td><?php echo (int) $row['book_count']; ?></td>
                            <td><a class="btn btn-sm btn-outline-primary"
                                    href="fix-author.php?id=<?php echo (int) $row['id']; ?>">Sửa</a> <a
                                    class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá tác giả?')"
                                    href="author.php?delete=<?php echo (int) $row['id']; ?>">Xoá</a></td>
                        </tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>
        <div class="mt-3"><?php render_pagination($page, $totalPages); ?></div>
    </div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>