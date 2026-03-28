<?php
$page_title = 'Phân loại';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if (!empty($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $count = fetch_count('SELECT COUNT(*) FROM book_category WHERE category_id = ' . $id);
    if ($count > 0) {
        flash('warning', 'Phân loại đang được gán cho sản phẩm nên không thể xoá.');
    } else {
        mysqli_query(db(), 'DELETE FROM categories WHERE id = ' . $id);
        flash('success', 'Đã xoá phân loại.');
    }
    redirect('category.php');
}

$rows = fetch_all('SELECT c.*, (SELECT COUNT(*) FROM book_category bc WHERE bc.category_id = c.id) AS book_count FROM categories c ORDER BY c.id DESC');

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid"><div class="iq-card"><div class="iq-card-header d-flex justify-content-between align-items-center"><h4 class="card-title mb-0">Quản lý phân loại</h4><a class="btn btn-primary" href="add-category.php">Thêm phân loại</a></div><div class="iq-card-body table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>#</th><th>Tên phân loại</th><th>Thông tin</th><th>Số sản phẩm</th><th>Thao tác</th></tr></thead><tbody><?php $stt=1; foreach ($rows as $row): ?><tr><td><?php echo $stt++; ?></td><td><?php echo h($row['name']); ?></td><td><?php echo h($row['info']); ?></td><td><?php echo (int) $row['book_count']; ?></td><td><a class="btn btn-sm btn-outline-primary" href="fix-category.php?id=<?php echo (int) $row['id']; ?>">Sửa</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Xoá phân loại?')" href="category.php?delete=<?php echo (int) $row['id']; ?>">Xoá</a></td></tr><?php endforeach; ?></tbody></table></div></div></div></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
