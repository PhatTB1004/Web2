<?php
$page_title = 'Thêm phiếu nhập';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$books = fetch_all("SELECT b.id, b.book_code, b.bookname, a.fullname AS author_name FROM books b LEFT JOIN authors a ON a.id = b.author_id ORDER BY b.bookname");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $note = trim($_POST['note'] ?? '');
    $bookIds = $_POST['book_id'] ?? [];
    $qtys = $_POST['quantity'] ?? [];
    $prices = $_POST['import_price'] ?? [];

    mysqli_begin_transaction(db());
    try {
        $stmt = mysqli_prepare(db(), 'INSERT INTO imports (`date`, status, note, total_amount) VALUES (?, ?, ?, 0)');
        $status = 'draft';
        mysqli_stmt_bind_param($stmt, 'sss', $date, $status, $note);
        mysqli_stmt_execute($stmt);
        $importId = mysqli_insert_id(db());
        mysqli_stmt_close($stmt);

        $total = 0.0;
        $stmt = mysqli_prepare(db(), 'INSERT INTO import_items (import_id, book_id, import_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)');
        foreach ($bookIds as $i => $bookIdRaw) {
            $bookId = (int) $bookIdRaw;
            $qty = (int) ($qtys[$i] ?? 0);
            $price = (float) ($prices[$i] ?? 0);
            if ($bookId <= 0 || $qty <= 0) {
                continue;
            }
            $subtotal = $qty * $price;
            $total += $subtotal;
            mysqli_stmt_bind_param($stmt, 'iidid', $importId, $bookId, $price, $qty, $subtotal);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);

        if ($total <= 0) {
            throw new Exception('Vui lòng thêm ít nhất một sản phẩm nhập hàng.');
        }
        $stmt = mysqli_prepare(db(), 'UPDATE imports SET total_amount = ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'di', $total, $importId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit(db());
        flash('success', 'Đã tạo phiếu nhập nháp.');
        redirect('fix-import.php?id=' . $importId);
    } catch (Throwable $e) {
        mysqli_rollback(db());
        flash('danger', $e->getMessage());
        redirect('add-import.php');
    }
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page"><div class="container-fluid"><div class="iq-card"><div class="iq-card-header"><h4 class="card-title mb-0">Thêm phiếu nhập</h4></div><div class="iq-card-body">
<form method="post">
   <div class="row">
      <div class="col-md-4 form-group"><label>Ngày nhập</label><input type="date" name="date" class="form-control" value="<?php echo h(date('Y-m-d')); ?>" required></div>
      <div class="col-md-8 form-group"><label>Ghi chú</label><input name="note" class="form-control"></div>
   </div>
   <div id="importRows">
      <div class="row import-row">
         <div class="col-md-5 form-group"><label>Sách</label><select name="book_id[]" class="form-control" required><option value="">-- Chọn --</option><?php foreach ($books as $b): ?><option value="<?php echo (int) $b['id']; ?>"><?php echo h($b['book_code'] . ' - ' . $b['bookname'] . ' (' . $b['author_name'] . ')'); ?></option><?php endforeach; ?></select></div>
         <div class="col-md-3 form-group"><label>Số lượng</label><input type="number" min="1" step="1" name="quantity[]" class="form-control" required></div>
         <div class="col-md-4 form-group"><label>Giá nhập</label><input type="number" min="0" step="0.01" name="import_price[]" class="form-control" required></div>
      </div>
   </div>
   <button type="button" class="btn btn-outline-secondary mb-3" id="addRow">Thêm dòng</button>
   <div><button class="btn btn-primary">Lưu nháp</button> <a href="import.php" class="btn btn-secondary">Quay lại</a></div>
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
