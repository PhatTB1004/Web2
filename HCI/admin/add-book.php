<?php
$page_title = 'Thêm sản phẩm';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$categories = fetch_all('SELECT * FROM categories ORDER BY name');
$authors = fetch_all('SELECT * FROM authors ORDER BY fullname');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookCode = trim($_POST['book_code'] ?? '') ?: next_code('books', 'BK', 'book_code', 3);
    $bookname = trim($_POST['bookname'] ?? '');
    $authorId = (int) ($_POST['author_id'] ?? 0);
    $info = trim($_POST['info'] ?? '');
    $costPrice = (float) ($_POST['cost_price'] ?? 0);
    $profitPercent = (float) ($_POST['profit_percent'] ?? app_setting('default_profit_percent', 20));
    $stockQuantity = (int) ($_POST['stock_quantity'] ?? 0);
    $status = $_POST['status'] ?? 'active';
    $categoryIds = $_POST['category_ids'] ?? [];
    $image = upload_file('image', '../images/books', null) ?? '';
    $sellPrice = calc_sale_price($costPrice, $profitPercent);

    if ($bookname === '' || $authorId <= 0) {
        if ($image) {
            delete_file_if_exists($image);
        }
        flash('danger', 'Tên sách và tác giả là bắt buộc.');
        redirect('add-book.php');
    }

    mysqli_begin_transaction(db());
    try {
        $stmt = mysqli_prepare(db(), 'INSERT INTO books (image, bookname, book_code, author_id, info, cost_price, profit_percent, sell_price, stock_quantity, updated_at, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)');
        mysqli_stmt_bind_param($stmt, 'sssisdddis', $image, $bookname, $bookCode, $authorId, $info, $costPrice, $profitPercent, $sellPrice, $stockQuantity, $status);
        mysqli_stmt_execute($stmt);
        $bookId = (int) mysqli_insert_id(db());
        mysqli_stmt_close($stmt);

        save_book_categories($bookId, $categoryIds);

        if ($stockQuantity > 0) {
            create_completed_import_entry($bookId, $stockQuantity, $costPrice);
        }

        mysqli_commit(db());
        flash('success', 'Đã thêm sản phẩm.');
        redirect('books.php');
    } catch (Throwable $e) {
        mysqli_rollback(db());
        if ($image) {
            delete_file_if_exists($image);
        }
        flash('danger', $e->getMessage());
        redirect('add-book.php');
    }
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

    <div class="container-fluid">
        <div class="iq-card">
            <div class="iq-card-header">
                <h4 class="card-title mb-0">Thêm sản phẩm</h4>
            </div>
            <div class="iq-card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 form-group"><label>Mã sách</label><input name="book_code"
                                class="form-control" value="<?php echo h(next_code('books','BK','book_code',3)); ?>">
                        </div>
                        <div class="col-md-8 form-group"><label>Tên sách</label><input name="bookname"
                                class="form-control" required></div>
                        <div class="col-md-6 form-group"><label>Tác giả</label><select name="author_id"
                                class="form-control" required>
                                <option value="">-- Chọn --</option><?php foreach ($authors as $a): ?><option
                                    value="<?php echo (int) $a['id']; ?>"><?php echo h($a['fullname']); ?></option>
                                <?php endforeach; ?>
                            </select></div>
                        <div class="col-md-6 form-group"><label>Ảnh</label><input type="file" name="image"
                                class="form-control-file" accept="image/*"></div>
                        <div class="col-md-12 form-group"><label>Phân loại</label><select name="category_ids[]"
                                class="form-control" multiple required><?php foreach ($categories as $cat): ?><option
                                    value="<?php echo (int) $cat['id']; ?>"><?php echo h($cat['name']); ?></option>
                                <?php endforeach; ?></select><small class="text-muted">Giữ Ctrl để chọn nhiều phân
                                loại.</small></div>
                        <div class="col-md-12 form-group"><label>Mô tả</label><textarea name="info" rows="4"
                                class="form-control"></textarea></div>
                        <div class="col-md-3 form-group"><label>Giá vốn</label><input type="number" step="0.01" min="0"
                                name="cost_price" class="form-control" value="0"></div>
                        <div class="col-md-3 form-group"><label>% Lợi Nhuận</label><input type="number" step="0.01" min="0" name="profit_percent" class="form-control" value="<?php echo h(app_setting('default_profit_percent', 20)); ?>"></div>
                        <div class="col-md-3 form-group"><label>Số lượng tồn đầu</label><input type="number" step="1"
                                min="0" name="stock_quantity" class="form-control" value="0"></div>
                        <div class="col-md-3 form-group"><label>Hiện trạng</label><select name="status"
                                class="form-control">
                                <option value="active">Hiển thị</option>
                                <option value="hidden">Ẩn</option>
                            </select></div>
                    </div><button class="btn btn-primary">Lưu</button> <a href="books.php"
                        class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>