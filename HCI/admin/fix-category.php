<?php
$page_title = 'Sửa phân loại';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$row = fetch_one('SELECT * FROM categories WHERE id = ' . $id);
if (!$row) {
    flash('danger', 'Không tìm thấy phân loại.');
    redirect('category.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $info = trim($_POST['info'] ?? '');
    if ($name === '') {
        flash('danger', 'Tên phân loại không được để trống.');
        redirect('fix-category.php?id=' . $id);
    }
    $stmt = mysqli_prepare(db(), 'UPDATE categories SET name = ?, info = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $info, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    flash('success', 'Đã cập nhật phân loại.');
    redirect('category.php');
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid"><div class="iq-card"><div class="iq-card-header"><h4 class="card-title mb-0">Sửa phân loại</h4></div><div class="iq-card-body"><form method="post"><input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>"><div class="form-group"><label>Tên phân loại</label><input name="name" class="form-control" value="<?php echo h($row['name']); ?>" required></div><div class="form-group"><label>Thông tin</label><textarea name="info" rows="4" class="form-control"><?php echo h($row['info']); ?></textarea></div><button class="btn btn-primary">Lưu</button> <a href="category.php" class="btn btn-secondary">Quay lại</a></form></div></div></div></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
