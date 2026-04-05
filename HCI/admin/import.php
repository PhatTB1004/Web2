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

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = trim($_GET['status'] ?? '');
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
$total = fetch_count("SELECT COUNT(*) FROM imports WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$rows = fetch_all("SELECT * FROM imports WHERE {$where} ORDER BY `date` DESC, id DESC LIMIT {$offset}, {$perPage}");

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="iq-card mb-4">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Phiếu nhập</h4><a class="btn btn-primary" href="add-import.php">Tạo phiếu nhập</a>
            </div>
            <div class="iq-card-body">
                <form class="row">
                    <div class="col-md-3 form-group"><label>Từ ngày</label><input type="date" name="from" class="form-control" value="<?php echo h($from); ?>"></div>
                    <div class="col-md-3 form-group"><label>Đến ngày</label><input type="date" name="to" class="form-control" value="<?php echo h($to); ?>"></div>
                    <div class="col-md-3 form-group"><label>Trạng thái</label><select name="status" class="form-control"><option value="">Tất cả</option><option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>draft</option><option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>completed</option></select></div>
                    <div class="col-md-3 form-group align-self-end"><button class="btn btn-primary">Lọc</button></div>
                </form>
            </div>
        </div>
        <div class="iq-card">
            <div class="iq-card-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Ngày</th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
                            <th>Tổng tiền</th>
                            <th>Chi tiết</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody><?php $stt = $offset + 1; foreach ($rows as $row): ?><tr>
                            <td><?php echo 'PN' . str_pad((string) $row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo h($row['date']); ?></td>
                            <td><span class="<?php echo h(import_status_badge($row['status'])); ?>"><?php echo h($row['status']); ?></span></td>
                            <td><?php echo h($row['note']); ?></td>
                            <td><?php echo vn_money($row['total_amount']); ?> ₫</td>
                            <td><a class="btn btn-sm btn-info" href="fix-import.php?id=<?php echo (int) $row['id']; ?>&action=view">Xem chi tiết</a></td>
                            <td>
                                <?php if ($row['status'] !== 'completed'): ?>
                                <a class="btn btn-sm btn-primary" href="fix-import.php?id=<?php echo (int) $row['id']; ?>&action=edit">Sửa</a>
                                <a class="btn btn-sm btn-success" href="import.php?complete=<?php echo (int) $row['id']; ?>" onclick="return confirm('Hoàn thành phiếu nhập?')">Hoàn thành</a>
                                <?php else: ?>Đã hoàn thành<?php endif; ?>
                            </td>
                        </tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>
        <div class="mt-3"><?php render_pagination($page, $totalPages, ['from' => $from, 'to' => $to, 'status' => $status]); ?></div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
