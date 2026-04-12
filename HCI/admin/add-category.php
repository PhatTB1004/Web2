<?php
$page_title = 'Thêm phân loại';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $info = trim($_POST['info'] ?? '');
    if ($name === '') {
        flash('danger', 'Tên phân loại không được để trống.');
        redirect('add-category.php');
    }
    $stmt = mysqli_prepare(db(), 'INSERT INTO categories (name, info) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'ss', $name, $info);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    flash('success', 'Đã thêm phân loại.');
    redirect('category.php');
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

    <div class="container-fluid">
        <div class="iq-card">
            <div class="iq-card-header">
                <h4 class="card-title mb-0">Thêm phân loại</h4>
            </div>
            <div class="iq-card-body">
                <form method="post">
                    <div class="form-group"><label>Tên phân loại</label><input name="name" class="form-control"
                            required></div>
                    <div class="form-group"><label>Thông tin</label><textarea name="info" rows="4"
                            class="form-control"></textarea></div><button class="btn btn-primary">Lưu</button> <a
                        href="category.php" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>