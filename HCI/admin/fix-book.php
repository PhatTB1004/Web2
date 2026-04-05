<?php
$page_title = 'Sửa sản phẩm';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$categories = fetch_all('SELECT * FROM categories ORDER BY name');
$authors = fetch_all('SELECT * FROM authors ORDER BY fullname');
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$book = fetch_one('SELECT * FROM books WHERE id = ' . $id);
if (!$book) {
    flash('danger', 'Không tìm thấy sản phẩm.');
    redirect('books.php');
}
$selectedCategories = get_book_category_ids($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookname = trim($_POST['bookname'] ?? '');
    $authorId = (int) ($_POST['author_id'] ?? 0);
    $info = trim($_POST['info'] ?? '');
    $profitPercent = (float) ($_POST['profit_percent'] ?? $book['profit_percent']);
    $status = $_POST['status'] ?? 'visible';
    $categoryIds = $_POST['category_ids'] ?? [];
    $removeImage = !empty($_POST['remove_image']);
    $image = $book['image'];
    $costPrice = (float) $book['cost_price'];
    $stockQuantity = (int) $book['stock_quantity'];

    if ($removeImage && $image) {
        delete_file_if_exists('images/books', $image);
        $image = '';
    }
    $newImage = upload_file('image', 'images/books', $image);
    if ($newImage !== null && $newImage !== $image) {
        if ($image) {
            delete_file_if_exists('images/books', $image);
        }
        $image = $newImage ?? '';
    }

    if ($bookname === '' || $authorId <= 0) {
        flash('danger', 'Tên sách và tác giả là bắt buộc.');
        redirect('fix-book.php?id=' . $id);
    }

    $sellPrice = calc_sale_price($costPrice, $profitPercent);
    $stmt = mysqli_prepare(db(), 'UPDATE books SET image = ?, bookname = ?, author_id = ?, info = ?, profit_percent = ?, sell_price = ?, updated_at = NOW(), status = ? WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'ssisddsi', $image, $bookname, $authorId, $info, $profitPercent, $sellPrice, $status, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    save_book_categories($id, $categoryIds);

    flash('success', 'Đã cập nhật sản phẩm.');
    redirect('books.php');
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid"><div class="iq-card"><div class="iq-card-header"><h4 class="card-title mb-0">Sửa sản phẩm</h4></div><div class="iq-card-body"><form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?php echo (int) $book['id']; ?>"><div class="row"><div class="col-md-4 form-group"><label>Mã sách</label><input class="form-control" value="<?php echo h($book['book_code']); ?>" disabled></div><div class="col-md-8 form-group"><label>Tên sách</label><input name="bookname" class="form-control" value="<?php echo h($book['bookname']); ?>" required></div><div class="col-md-6 form-group"><label>Tác giả</label><select name="author_id" class="form-control" required><?php foreach ($authors as $a): ?><option value="<?php echo (int) $a['id']; ?>" <?php echo ((int) $book['author_id'] === (int) $a['id']) ? 'selected' : ''; ?>><?php echo h($a['fullname']); ?></option><?php endforeach; ?></select></div><div class="col-md-6 form-group"><label>Ảnh hiện tại</label><div class="mb-2"><?php if (!empty($book['image'])): ?><img src="../images/books/<?php echo h($book['image']); ?>" style="max-width:120px;border-radius:8px;" alt=""><?php else: ?>Chưa có ảnh<?php endif; ?></div><label><input type="checkbox" name="remove_image" value="1"> Bỏ hình</label><input type="file" name="image" class="form-control-file mt-2" accept="image/*"></div><div class="col-md-12 form-group"><label>Phân loại</label><select name="category_ids[]" class="form-control" multiple required><?php foreach ($categories as $cat): ?><option value="<?php echo (int) $cat['id']; ?>" <?php echo in_array((int) $cat['id'], $selectedCategories, true) ? 'selected' : ''; ?>><?php echo h($cat['name']); ?></option><?php endforeach; ?></select></div><div class="col-md-12 form-group"><label>Mô tả</label><textarea name="info" rows="4" class="form-control"><?php echo h($book['info']); ?></textarea></div><div class="col-md-3 form-group"><label>Giá vốn</label><input type="number" step="0.01" min="0" class="form-control" value="<?php echo h($book['cost_price']); ?>" readonly><small class="text-muted">Giá vốn được ghi nhận qua phiếu nhập.</small></div><div class="col-md-3 form-group"><label>% lợi nhuận</label><input type="number" step="0.01" min="0" name="profit_percent" class="form-control" value="<?php echo h($book['profit_percent']); ?>"></div><div class="col-md-3 form-group"><label>Số lượng tồn</label><input type="number" step="1" min="0" class="form-control" value="<?php echo (int) $book['stock_quantity']; ?>" readonly><small class="text-muted">Tồn kho được cập nhật qua nhập hàng và đơn hàng.</small></div><div class="col-md-3 form-group"><label>Hiện trạng</label><select name="status" class="form-control"><option value="visible" <?php echo $book['status'] === 'visible' ? 'selected' : ''; ?>>visible</option><option value="hidden" <?php echo $book['status'] === 'hidden' ? 'selected' : ''; ?>>hidden</option></select></div></div><button class="btn btn-primary">Lưu</button> <a href="books.php" class="btn btn-secondary">Quay lại</a></form></div></div></div></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
