<?php
$page_title = 'Thêm tác giả';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $info = trim($_POST['info'] ?? '');
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image = upload_file('image', 'images/authors', null) ?? '';
    }
    if ($fullname === '') {
        flash('danger', 'Tên tác giả không được để trống.');
        redirect('add-author.php');
    }
    $stmt = mysqli_prepare(db(), 'INSERT INTO authors (image, fullname, info) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $image, $fullname, $info);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    flash('success', 'Đã thêm tác giả.');
    redirect('author.php');
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid"><div class="iq-card"><div class="iq-card-header"><h4 class="card-title mb-0">Thêm tác giả</h4></div><div class="iq-card-body"><form method="post" enctype="multipart/form-data"><div class="form-group"><label>Họ tên</label><input name="fullname" class="form-control" required></div><div class="form-group"><label>Ảnh</label><input type="file" name="image" class="form-control-file" accept="image/*"></div><div class="form-group"><label>Thông tin</label><textarea name="info" rows="4" class="form-control"></textarea></div><button class="btn btn-primary">Lưu</button> <a href="author.php" class="btn btn-secondary">Quay lại</a></form></div></div></div></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
