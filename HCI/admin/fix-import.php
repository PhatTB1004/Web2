<?php
$page_title = 'Sửa phiếu nhập';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$import = fetch_one('SELECT * FROM imports WHERE id = ' . $id);
if (!$import) {
    flash('danger', 'Không tìm thấy phiếu nhập.');
    redirect('import.php');
}
if ($import['status'] === 'completed') {
    flash('warning', 'Phiếu nhập đã hoàn thành, không thể chỉnh sửa.');
    redirect('import.php');
}

$books = fetch_all("SELECT b.id, b.book_code, b.bookname, a.fullname AS author_name FROM books b LEFT JOIN authors a ON a.id = b.author_id ORDER BY b.bookname");
$items = fetch_all('SELECT * FROM import_items WHERE import_id = ' . $id . ' ORDER BY id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? $import['date'];
    $note = trim($_POST['note'] ?? '');
    $statusAction = $_POST['submit_action'] ?? 'save';
    $bookIds = $_POST['book_id'] ?? [];
    $qtys = $_POST['quantity'] ?? [];
    $prices = $_POST['import_price'] ?? [];

    mysqli_begin_transaction(db());
    try {
        $stmt = mysqli_prepare(db(), 'UPDATE imports SET `date` = ?, note = ?, status = ? WHERE id = ?');
        $status = 'draft';
        mysqli_stmt_bind_param($stmt, 'sssi', $date, $note, $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_query(db(), 'DELETE FROM import_items WHERE import_id = ' . $id);

        $stmt = mysqli_prepare(db(), 'INSERT INTO import_items (import_id, book_id, import_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)');
        $total = 0.0;
        foreach ($bookIds as $i => $bookIdRaw) {
            $bookId = (int) $bookIdRaw;
            $qty = (int) ($qtys[$i] ?? 0);
            $price = (float) ($prices[$i] ?? 0);
            if ($bookId <= 0 || $qty <= 0) {
                continue;
            }
            $subtotal = $qty * $price;
            $total += $subtotal;
            mysqli_stmt_bind_param($stmt, 'iidid', $id, $bookId, $price, $qty, $subtotal);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);

        if ($total <= 0) {
            throw new Exception('Vui lòng nhập ít nhất một sản phẩm.');
        }
        $stmt = mysqli_prepare(db(), 'UPDATE imports SET total_amount = ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'di', $total, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit(db());

        if ($statusAction === 'complete') {
            if (!complete_import($id)) {
                throw new Exception('Không thể hoàn thành phiếu nhập.');
            }
            flash('success', 'Đã hoàn thành phiếu nhập.');
        } else {
            flash('success', 'Đã lưu phiếu nhập nháp.');
        }
        redirect('import.php');
    } catch (Throwable $e) {
        mysqli_rollback(db());
        flash('danger', $e->getMessage());
        redirect('fix-import.php?id=' . $id);
    }
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid"><div class="iq-card"><div class="iq-card-header"><h4 class="card-title mb-0">Sửa phiếu nhập</h4></div><div class="iq-card-body">
<form method="post">
   <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
   <div class="row">
      <div class="col-md-4 form-group"><label>Ngày nhập</label><input type="date" name="date" class="form-control" value="<?php echo h($import['date']); ?>" required></div>
      <div class="col-md-8 form-group"><label>Ghi chú</label><input name="note" class="form-control" value="<?php echo h($import['note']); ?>"></div>
   </div>
   <div id="importRows">
      <?php foreach ($items as $item): ?>
      <div class="row import-row">
         <div class="col-md-5 form-group"><label>Sách</label><select name="book_id[]" class="form-control" required><?php foreach ($books as $b): ?><option value="<?php echo (int) $b['id']; ?>" <?php echo ((int) $item['book_id'] === (int) $b['id']) ? 'selected' : ''; ?>><?php echo h($b['book_code'] . ' - ' . $b['bookname'] . ' (' . $b['author_name'] . ')'); ?></option><?php endforeach; ?></select></div>
         <div class="col-md-3 form-group"><label>Số lượng</label><input type="number" min="1" step="1" name="quantity[]" class="form-control" value="<?php echo (int) $item['quantity']; ?>" required></div>
         <div class="col-md-4 form-group"><label>Giá nhập</label><input type="number" min="0" step="0.01" name="import_price[]" class="form-control" value="<?php echo h($item['import_price']); ?>" required></div>
      </div>
      <?php endforeach; ?>
   </div>
   <button type="button" class="btn btn-outline-secondary mb-3" id="addRow">Thêm dòng</button>
   <div><button class="btn btn-primary" name="submit_action" value="save">Lưu nháp</button> <button class="btn btn-success" name="submit_action" value="complete">Hoàn thành</button> <a href="import.php" class="btn btn-secondary">Quay lại</a></div>
</form>
<script>
(function(){
  const btn = document.getElementById('addRow');
  const rows = document.getElementById('importRows');
  btn.addEventListener('click', function(){
    const first = rows.querySelector('.import-row');
    const clone = first.cloneNode(true);
    clone.querySelectorAll('input').forEach(i => i.value = '');
    clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    rows.appendChild(clone);
  });
})();
</script>
</div></div></div></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
