<?php
$page_title = 'Sửa phiếu nhập';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$mode = $_GET['action'] ?? $_POST['action'] ?? 'edit';
$isView = ($mode === 'view');

$import = fetch_one('SELECT * FROM imports WHERE id = ' . $id);
if (!$import) {
    flash('danger', 'Không tìm thấy phiếu nhập.');
    redirect('import.php');
}

if ($import['status'] === 'completed' && !$isView) {
    flash('warning', 'Phiếu nhập đã hoàn thành, không thể chỉnh sửa.');
    redirect('import.php');
}

$books = fetch_all("
    SELECT b.id, b.book_code, b.bookname, a.fullname AS author_name
    FROM books b
    LEFT JOIN authors a ON a.id = b.author_id
    ORDER BY b.bookname
");

$items = fetch_all('
    SELECT ii.*, b.book_code, b.bookname, a.fullname AS author_name
    FROM import_items ii
    LEFT JOIN books b ON b.id = ii.book_id
    LEFT JOIN authors a ON a.id = b.author_id
    WHERE ii.import_id = ' . $id . '
    ORDER BY ii.id
');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isView) {
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

        $stmt = mysqli_prepare(db(), '
            INSERT INTO import_items (import_id, book_id, import_price, quantity, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ');

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
        redirect('fix-import.php?id=' . $id . '&action=edit');
    }
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="iq-card">
         <div class="iq-card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">
               <?php echo $isView ? 'Chi tiết phiếu nhập' : 'Sửa phiếu nhập'; ?>
            </h4>
            <a href="import.php" class="btn btn-secondary">Quay lại</a>
         </div>

         <div class="iq-card-body">
            <div class="row mb-4">
               <div class="col-md-4">
                  <strong>Mã phiếu:</strong> PN<?php echo str_pad((string) $import['id'], 3, '0', STR_PAD_LEFT); ?>
               </div>
               <div class="col-md-4">
                  <strong>Ngày nhập:</strong> <?php echo h($import['date']); ?>
               </div>
               <div class="col-md-4">
                  <strong>Trạng thái:</strong>
                  <span class="<?php echo h(import_status_badge($import['status'])); ?>">
                     <?php echo h($import['status']); ?>
                  </span>
               </div>
               <div class="col-md-12 mt-2">
                  <strong>Ghi chú:</strong> <?php echo h($import['note']); ?>
               </div>
            </div>

            <?php if ($isView): ?>
               <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Sách</th>
                           <th>Tác giả</th>
                           <th>Số lượng</th>
                           <th>Giá nhập</th>
                           <th>Thành tiền</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($items as $i => $item): ?>
                           <tr>
                              <td><?php echo $i + 1; ?></td>
                              <td><?php echo h(($item['book_code'] ?? '') . ' - ' . ($item['bookname'] ?? '')); ?></td>
                              <td><?php echo h($item['author_name'] ?? ''); ?></td>
                              <td><?php echo (int) $item['quantity']; ?></td>
                              <td><?php echo vn_money($item['import_price']); ?> ₫</td>
                              <td><?php echo vn_money($item['subtotal']); ?> ₫</td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>

               <div class="mt-3">
                  <strong>Tổng tiền:</strong> <?php echo vn_money($import['total_amount']); ?> ₫
               </div>

            <?php else: ?>
               <form method="post">
                  <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
                  <input type="hidden" name="action" value="edit">

                  <div class="row">
                     <div class="col-md-4 form-group">
                        <label>Ngày nhập</label>
                        <input type="date" name="date" class="form-control"
                               value="<?php echo h($import['date']); ?>" required>
                     </div>
                     <div class="col-md-8 form-group">
                        <label>Ghi chú</label>
                        <input name="note" class="form-control" value="<?php echo h($import['note']); ?>">
                     </div>
                  </div>

                  <div id="importRows">
                     <?php foreach ($items as $item): ?>
                     <div class="row import-row">
                        <div class="col-md-5 form-group">
                           <label>Sách</label>
                           <select name="book_id[]" class="form-control" required>
                              <?php foreach ($books as $b): ?>
                                 <option value="<?php echo (int) $b['id']; ?>"
                                    <?php echo ((int) $item['book_id'] === (int) $b['id']) ? 'selected' : ''; ?>>
                                    <?php echo h($b['book_code'] . ' - ' . $b['bookname'] . ' (' . $b['author_name'] . ')'); ?>
                                 </option>
                              <?php endforeach; ?>
                           </select>
                        </div>

                        <div class="col-md-3 form-group">
                           <label>Số lượng</label>
                           <input type="number" min="1" step="1" name="quantity[]"
                                  class="form-control" value="<?php echo (int) $item['quantity']; ?>" required>
                        </div>

                        <div class="col-md-4 form-group">
                           <label>Giá nhập</label>
                           <input type="number" min="0" step="0.01" name="import_price[]"
                                  class="form-control" value="<?php echo h($item['import_price']); ?>" required>
                        </div>
                     </div>
                     <?php endforeach; ?>
                  </div>

                  <button type="button" class="btn btn-outline-secondary mb-3" id="addRow">Thêm dòng</button>

                  <div>
                     <button class="btn btn-primary" name="submit_action" value="save">Lưu nháp</button>
                     <button class="btn btn-success" name="submit_action" value="complete">Hoàn thành</button>
                  </div>
               </form>

               <script>
               (function() {
                  const btn = document.getElementById('addRow');
                  const rows = document.getElementById('importRows');

                  btn.addEventListener('click', function() {
                     const first = rows.querySelector('.import-row');
                     const clone = first.cloneNode(true);
                     clone.querySelectorAll('input').forEach(i => i.value = '');
                     clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                     rows.appendChild(clone);
                  });
               })();
               </script>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>