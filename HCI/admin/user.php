<?php
$page_title = 'Người dùng';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if (!empty($_GET['action']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = (string) $_GET['action'];
    $user = fetch_one("SELECT * FROM users WHERE id = {$id}");
    $me = current_admin();

    if ($user) {
        $isSelf = ((int) $user['id'] === (int) ($me['id'] ?? 0));
        $isAdmin = (($user['role'] ?? '') === 'admin');

        if ($isAdmin) {
            flash('warning', 'Không thể thao tác với tài khoản quản trị.');
            redirect('user.php');
        }

        if ($action === 'lock') {
            if ($isSelf) {
                flash('warning', 'Không thể khoá chính tài khoản đang đăng nhập.');
            } else {
                mysqli_query(db(), "UPDATE users SET status = 'locked' WHERE id = {$id}");
                flash('success', 'Đã khoá tài khoản.');
            }
        } elseif ($action === 'unlock') {
            mysqli_query(db(), "UPDATE users SET status = 'active' WHERE id = {$id}");
            flash('success', 'Đã mở khoá tài khoản.');
        } elseif ($action === 'reset') {
            $temp = 'MK' . random_int(100000, 999999);
            $hash = password_hash($temp, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare(db(), 'UPDATE users SET password = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'si', $hash, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            flash('success', 'Mật khẩu tạm thời của ' . ($user['username'] ?? '') . ': ' . $temp);
        }
    }

    redirect('user.php');
}

$keyword = trim($_GET['keyword'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$where = "role <> 'admin'";

if ($keyword !== '') {
    $kw = mysqli_real_escape_string(db(), $keyword);
    $where .= " AND (username LIKE '%{$kw}%' OR fullname LIKE '%{$kw}%' OR email LIKE '%{$kw}%' OR phone LIKE '%{$kw}%')";
}
if ($status !== '') {
    $where .= " AND status = '" . mysqli_real_escape_string(db(), $status) . "'";
}

$sortMap = [
    'username' => 'username',
    'fullname' => 'fullname',
    'email' => 'email',
    'phone' => 'phone',
    'status' => 'status',
];
[$sort, $dir] = list_sort_state($sortMap, 'fullname', 'asc');
$orderBy = list_sort_clause($sortMap, $sort, $dir, 'fullname') . ', id DESC';

$total = fetch_count("SELECT COUNT(*) FROM users WHERE {$where}");
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}
$users = fetch_all("SELECT id, username, fullname, email, phone, status FROM users WHERE {$where} {$orderBy} LIMIT {$offset}, {$perPage}");

$baseQuery = ['keyword' => $keyword, 'status' => $status, 'sort' => $sort, 'dir' => $dir];

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>

   <div class="container-fluid">
      <div class="iq-card mb-4">
         <div class="iq-card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Quản lý người dùng</h4>
            <a class="btn btn-primary" href="add-user.php">Thêm tài khoản</a>
         </div>
         <div class="iq-card-body">
            <form class="row" method="get">
               <div class="col-md-5 form-group">
                  <label>Tìm kiếm</label>
                  <input name="keyword" class="form-control" value="<?php echo h($keyword); ?>" placeholder="Tên đăng nhập, họ tên, email, SĐT">
               </div>
               <div class="col-md-3 form-group">
                  <label>Trạng thái</label>
                  <select name="status" class="form-control">
                     <option value="">Tất cả</option>
                     <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Đang hoạt động</option>
                     <option value="locked" <?php echo $status === 'locked' ? 'selected' : ''; ?>>Đã khoá</option>
                  </select>
               </div>
               <input type="hidden" name="sort" value="<?php echo h($sort); ?>">
               <input type="hidden" name="dir" value="<?php echo h($dir); ?>">
               <div class="col-md-4 form-group align-self-end">
                  <button class="btn btn-primary">Lọc</button>
               </div>
            </form>
         </div>
      </div>

      <div class="iq-card">
         <div class="iq-card-body table-responsive">
            <table class="table table-striped table-bordered">
               <thead>
                  <tr>
                     <th>#</th>
                     <th><?php echo render_sortable_th('username', 'Tên đăng nhập', $baseQuery, $sort, $dir); ?></th>
                     <th><?php echo render_sortable_th('fullname', 'Họ tên', $baseQuery, $sort, $dir); ?></th>
                     <th><?php echo render_sortable_th('email', 'Email', $baseQuery, $sort, $dir); ?></th>
                     <th><?php echo render_sortable_th('phone', 'SĐT', $baseQuery, $sort, $dir); ?></th>
                     <th><?php echo render_sortable_th('status', 'Trạng thái', $baseQuery, $sort, $dir); ?></th>
                     <th>Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                  <?php $stt = $offset + 1; foreach ($users as $row): ?>
                  <tr>
                     <td><?php echo $stt++; ?></td>
                     <td><?php echo h($row['username']); ?></td>
                     <td><?php echo h($row['fullname']); ?></td>
                     <td><?php echo h($row['email']); ?></td>
                     <td><?php echo h($row['phone']); ?></td>
                     <td>
                        <span class="<?php echo h(user_status_badge($row['status'])); ?>">
                           <?php echo h($row['status'] === 'locked' ? 'Đã khoá' : 'Đang hoạt động'); ?>
                        </span>
                     </td>
                     <td>
                        <a class="btn btn-sm btn-outline-primary" href="user.php?action=reset&id=<?php echo (int) $row['id']; ?>">Reset mật khẩu</a>
                        <?php if ((int) $row['id'] === (int) (current_admin()['id'] ?? 0)): ?>
                           <span class="text-muted ml-2">Đang đăng nhập</span>
                        <?php elseif ($row['status'] === 'locked'): ?>
                           <a class="btn btn-sm btn-outline-success" href="user.php?action=unlock&id=<?php echo (int) $row['id']; ?>">Mở khoá</a>
                        <?php else: ?>
                           <a class="btn btn-sm btn-outline-danger" href="user.php?action=lock&id=<?php echo (int) $row['id']; ?>">Khoá</a>
                        <?php endif; ?>
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
