<?php
$page_title = 'Nhập hàng';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if (!empty($_GET['complete'])) {
    $id = (int) $_GET['complete'];
    if (complete_import($id)) {
        flash('success', 'Đã hoàn thành phiếu nhập.');
    }
    redirect('import.php');
}
if (!empty($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (delete_import($id)) {
        flash('success', 'Đã xoá phiếu nhập.');
    }
    redirect('import.php');
}

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = trim($_GET['status'] ?? '');
$q = trim($_GET['q'] ?? '');
$amountFrom = trim($_GET['amount_from'] ?? '');
$amountTo = trim($_GET['amount_to'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$where = '1=1';
if ($from !== '') {
    $where .= " AND `date` >= '" . mysqli_real_escape_string(db(), $from) . "'";
}
if ($to !== '') {
    $where .= " AND `date` <= '" . mysqli_real_escape_string(db(), $to) . "'";
}
if ($status !== '') {
    $where .= " AND status = '" . mysqli_real_escape_string(db(), $status) . "'";
}
if ($amountFrom !== '') {
    $where .= ' AND total_amount >= ' . (float) $amountFrom;
}
if ($amountTo !== '') {
    $where .= ' AND total_amount <= ' . (float) $amountTo;
}
if ($q !== '') {
    $qEsc = mysqli_real_escape_string(db(), $q);
    $where .= " AND (CAST(id AS CHAR) LIKE '%{$qEsc}%' OR note LIKE '%{$qEsc}%' OR EXISTS (SELECT 1 FROM import_items ii LEFT JOIN books b ON b.id = ii.book_id WHERE ii.import_id = imports.id AND (b.book_code LIKE '%{$qEsc}%' OR b.bookname LIKE '%{$qEsc}%')))";
}

$sortMap = [
    'date' => '`date`',
    'status' => 'status',
    'total_amount' => 'total_amount',
    'id' => 'id',
];
[$sort, $dir] = list_sort_state($sortMap, 'date', 'desc');
$orderBy = list_sort_clause($sortMap, $sort, $dir, 'date') . ', id DESC';

$total = fetch_count("SELECT COUNT(*) FROM imports WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$rows = fetch_all("SELECT * FROM imports WHERE {$where} {$orderBy} LIMIT {$offset}, {$perPage}");
$baseQuery = [
    'from' => $from,
    'to' => $to,
    'status' => $status,
    'q' => $q,
    'amount_from' => $amountFrom,
    'amount_to' => $amountTo,
    'sort' => $sort,
    'dir' => $dir,
];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

    <div class="container-fluid">
        <div class="iq-card mb-4">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Phiếu nhập</h4><a class="btn btn-primary" href="add-import.php">Tạo phiếu nhập</a>
            </div>
            <div class="iq-card-body">
                <form class="row">
                    <div class="col-md-3 form-group">
                        <label>Từ khóa</label>
                        <input type="text" name="q" class="form-control" value="<?php echo h($q); ?>" placeholder="Mã phiếu, ghi chú, tên sách">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Từ ngày</label>
                        <input type="date" name="from" class="form-control" value="<?php echo h($from); ?>">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Đến ngày</label>
                        <input type="date" name="to" class="form-control" value="<?php echo h($to); ?>">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Giá từ</label>
                        <input type="number" step="0.01" min="0" name="amount_from" class="form-control" value="<?php echo h($amountFrom); ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Giá đến</label>
                        <input type="number" step="0.01" min="0" name="amount_to" class="form-control" value="<?php echo h($amountTo); ?>">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Nháp</option>
                            <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                        </select>
                    </div>
                    <input type="hidden" name="sort" value="<?php echo h($sort); ?>">
                    <input type="hidden" name="dir" value="<?php echo h($dir); ?>">
                    <div class="col-12 form-group"><button class="btn btn-primary">Lọc</button></div>
                </form>
            </div>
        </div>
        <div class="iq-card">
            <div class="iq-card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo render_sortable_th('id', 'Mã', $baseQuery, $sort, $dir); ?></th>
                            <th><?php echo render_sortable_th('date', 'Ngày', $baseQuery, $sort, $dir); ?></th>
                            <th><?php echo render_sortable_th('status', 'Trạng thái', $baseQuery, $sort, $dir); ?></th>
                            <th>Ghi chú</th>
                            <th><?php echo render_sortable_th('total_amount', 'Tổng tiền', $baseQuery, $sort, $dir); ?></th>
                            <th>Chi tiết</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>PN<?php echo str_pad((string) $row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo h($row['date']); ?></td>
                            <td><span class="<?php echo h(import_status_badge($row['status'])); ?>"><?php echo h(import_status_text($row['status'])); ?></span></td>
                            <td><?php echo h($row['note']); ?></td>
                            <td><?php echo vn_money($row['total_amount']); ?> ₫</td>
                            <td><a class="btn btn-sm btn-info" href="fix-import.php?id=<?php echo (int) $row['id']; ?>&action=view">Xem chi tiết</a></td>
                            <td>
                                <?php if ($row['status'] !== 'completed'): ?>
                                <a class="btn btn-sm btn-primary" href="fix-import.php?id=<?php echo (int) $row['id']; ?>&action=edit">Sửa</a>
                                <a class="btn btn-sm btn-success" href="import.php?complete=<?php echo (int) $row['id']; ?>" onclick="return confirm('Hoàn thành phiếu nhập?')">Hoàn thành</a>
                                <a class="btn btn-sm btn-outline-danger" href="import.php?delete=<?php echo (int) $row['id']; ?>" onclick="return confirm('Xoá phiếu nhập này?')">Xoá</a>
                                <?php else: ?>Đã hoàn thành<?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3"><?php render_pagination($page, $totalPages, $baseQuery); ?></div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
