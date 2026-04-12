<?php
$page_title = 'Sửa tác giả';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$row = fetch_one('SELECT * FROM authors WHERE id = ' . $id);
if (!$row) {
    flash('danger', 'Không tìm thấy tác giả.');
    redirect('author.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $info = trim($_POST['info'] ?? '');
    $removeImage = !empty($_POST['remove_image']);
    $image = $row['image'];
    if ($removeImage && $image) {
        delete_file_if_exists('../images/authors', $image);
        $image = '';
    }
    $newImage = upload_file('image', '../images/authors', $image);
    if ($newImage !== $image) {
        if ($image) {
            delete_file_if_exists('../images/authors', $image);
        }
        $image = $newImage;
    }
    if ($fullname === '') {
        flash('danger', 'Tên tác giả không được để trống.');
        redirect('fix-author.php?id=' . $id);
    }
    $stmt = mysqli_prepare(db(), 'UPDATE authors SET image = ?, fullname = ?, info = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'sssi', $image, $fullname, $info, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    flash('success', 'Đã cập nhật tác giả.');
    redirect('author.php');
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div class="container-fluid">
    <div class="iq-card">
        <div class="iq-card-header">
            <h4 class="card-title mb-0">Sửa tác giả</h4>
        </div>
        <div class="iq-card-body">
            <form method="post" enctype="multipart/form-data"><input type="hidden" name="id"
                    value="<?php echo (int) $row['id']; ?>">
                <div class="form-group"><label>Họ tên</label><input name="fullname" class="form-control"
                        value="<?php echo h($row['fullname']); ?>" required></div>
                <div class="form-group"><label>Ảnh hiện tại</label>
                    <div class="mb-2"><?php if (!empty($row['image'])): ?><img
                            src="../images/authors/<?php echo h($row['image']); ?>" alt=""
                            style="max-width:120px;border-radius:8px;"> <?php else: ?>Chưa có ảnh<?php endif; ?></div>
                    <label><input type="checkbox" name="remove_image" value="1"> Bỏ hình</label>
                </div>
                <div class="form-group"><label>Chọn ảnh mới</label><input type="file" name="image"
                        class="form-control-file" accept="image/*"></div>
                <div class="form-group"><label>Thông tin</label><textarea name="info" rows="4"
                        class="form-control"><?php echo h($row['info']); ?></textarea></div><button
                    class="btn btn-primary">Lưu</button> <a href="author.php" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>