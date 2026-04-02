
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Cập nhật hồ sơ';
$user = current_user($conn);
if (!$user) {
    include 'includes/header.php';
    include 'includes/sidebar.php';
    include 'includes/topnav.php';
    ?>
    <div id="content-page" class="content-page">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="iq-card">
              <div class="iq-card-body text-center py-5">
                <h4 class="mb-3">Bạn cần đăng nhập</h4>
                <a href="sign-in.php" class="btn btn-primary">Đăng nhập</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'includes/footer.php'; exit;
}

$address = null;
$userId = (int) $user['id'];
$stmt = mysqli_prepare($conn, 'SELECT * FROM address WHERE user_id = ? ORDER BY is_default DESC, id DESC LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$address = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

$error = '';
$success = '';
$form = [
    'username' => $user['username'],
    'fullname' => $user['fullname'],
    'phone' => $user['phone'],
    'email' => $user['email'],
    'receiver_name' => $address['receiver_name'] ?? $user['fullname'],
    'address_detail' => $address['address_detail'] ?? '',
    'ward' => $address['ward'] ?? '',
    'district' => $address['district'] ?? '',
    'province' => $address['province'] ?? '',
    'is_default' => !empty($address['is_default']) ? 1 : 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['username'] = trim($_POST['username'] ?? '');
    $form['fullname'] = trim($_POST['fullname'] ?? '');
    $form['phone'] = trim($_POST['phone'] ?? '');
    $form['email'] = trim($_POST['email'] ?? '');
    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    $form['receiver_name'] = trim($_POST['receiver_name'] ?? '');
    $form['address_detail'] = trim($_POST['address_detail'] ?? '');
    $form['ward'] = trim($_POST['ward'] ?? '');
    $form['district'] = trim($_POST['district'] ?? '');
    $form['province'] = trim($_POST['province'] ?? '');
    $form['is_default'] = isset($_POST['is_default']) ? 1 : 0;

    if ($form['username'] === '' || $form['fullname'] === '' || $form['phone'] === '' || $form['email'] === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin chính.';
    } elseif (!preg_match('/^[A-Za-z0-9]+$/', $form['username'])) {
        $error = 'Tên tài khoản chỉ được dùng chữ cái và số.';
    } elseif (!preg_match('/^[A-Za-z0-9._%+-]+@gmail\.com$/i', $form['email'])) {
        $error = 'Email phải có đuôi @gmail.com.';
    } elseif (!preg_match('/^[0-9]{9,11}$/', $form['phone'])) {
        $error = 'Số điện thoại không hợp lệ.';
    } elseif (($newPassword !== '' || $confirmPassword !== '') && $currentPassword === '') {
        $error = 'Nhập mật khẩu hiện tại để đổi mật khẩu.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Mật khẩu mới không khớp.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT password FROM users WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        if ($row && !password_verify($currentPassword, $row['password'])) {
            $error = 'Mật khẩu hiện tại không đúng.';
        } else {
            $check = mysqli_prepare($conn, 'SELECT id FROM users WHERE (username = ? OR email = ?) AND id <> ? LIMIT 1');
            mysqli_stmt_bind_param($check, 'ssi', $form['username'], $form['email'], $userId);
            mysqli_stmt_execute($check);
            $result = mysqli_stmt_get_result($check);
            $exists = $result ? mysqli_fetch_assoc($result) : null;
            mysqli_stmt_close($check);

            if ($exists) {
                $error = 'Tên tài khoản hoặc email đã được dùng bởi tài khoản khác.';
            } else {
                mysqli_begin_transaction($conn);
                try {
                    if ($newPassword !== '') {
                        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $stmt = mysqli_prepare($conn, 'UPDATE users SET username = ?, fullname = ?, phone = ?, email = ?, password = ? WHERE id = ?');
                        mysqli_stmt_bind_param($stmt, 'sssssi', $form['username'], $form['fullname'], $form['phone'], $form['email'], $hash, $userId);
                    } else {
                        $stmt = mysqli_prepare($conn, 'UPDATE users SET username = ?, fullname = ?, phone = ?, email = ? WHERE id = ?');
                        mysqli_stmt_bind_param($stmt, 'ssssi', $form['username'], $form['fullname'], $form['phone'], $form['email'], $userId);
                    }
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    $stmt = mysqli_prepare($conn, 'INSERT INTO address (user_id, receiver_name, phone, address_detail, ward, district, province, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE receiver_name = VALUES(receiver_name), phone = VALUES(phone), address_detail = VALUES(address_detail), ward = VALUES(ward), district = VALUES(district), province = VALUES(province), is_default = VALUES(is_default)');
                    mysqli_stmt_bind_param($stmt, 'issssssi', $userId, $form['receiver_name'], $form['phone'], $form['address_detail'], $form['ward'], $form['district'], $form['province'], $form['is_default']);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    mysqli_commit($conn);
                    $success = 'Cập nhật hồ sơ thành công.';
                    $_SESSION['username'] = $form['username'];
                    $_SESSION['fullname'] = $form['fullname'];
                    $_SESSION['phone'] = $form['phone'];
                    $_SESSION['email'] = $form['email'];
                    unset($_SESSION['user_cache']);
                    $user = current_user($conn);
                } catch (Throwable $e) {
                    mysqli_rollback($conn);
                    $error = 'Không thể cập nhật hồ sơ.';
                }
            }
        }
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Cập nhật hồ sơ</h4>
                  <a href="profile.php" class="btn btn-outline-primary btn-sm">Quay lại</a>
               </div>
               <div class="iq-card-body">
                  <?php if ($error): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
                  <?php if ($success): ?><div class="alert alert-success"><?= h($success) ?></div><?php endif; ?>
                  <form method="post" action="">
                     <div class="row">
                        <div class="form-group col-md-4">
                           <label>Tên tài khoản</label>
                           <input type="text" name="username" class="form-control" value="<?= h($form['username']) ?>" pattern="[A-Za-z0-9]+" required>
                        </div>
                        <div class="form-group col-md-4">
                           <label>Họ và tên</label>
                           <input type="text" name="fullname" class="form-control" value="<?= h($form['fullname']) ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                           <label>Số điện thoại</label>
                           <input type="text" name="phone" class="form-control" value="<?= h($form['phone']) ?>" pattern="[0-9]{9,11}" required>
                        </div>
                        <div class="form-group col-md-6">
                           <label>Email</label>
                           <input type="email" name="email" class="form-control" value="<?= h($form['email']) ?>" pattern="[A-Za-z0-9._%+-]+@gmail\.com" required>
                        </div>
                        <div class="form-group col-md-6">
                           <label>Họ tên người nhận</label>
                           <input type="text" name="receiver_name" class="form-control" value="<?= h($form['receiver_name']) ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                           <label>Mật khẩu hiện tại</label>
                           <input type="password" name="current_password" class="form-control" placeholder="Nhập khi muốn đổi mật khẩu">
                        </div>
                        <div class="form-group col-md-4">
                           <label>Mật khẩu mới</label>
                           <input type="password" name="new_password" class="form-control" placeholder="Để trống nếu không đổi">
                        </div>
                        <div class="form-group col-md-4">
                           <label>Nhập lại mật khẩu mới</label>
                           <input type="password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu mới">
                        </div>
                        <div class="form-group col-md-4">
                           <label>Tỉnh/Thành phố</label>
                           <input type="text" name="province" class="form-control" value="<?= h($form['province']) ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                           <label>Quận/Huyện</label>
                           <input type="text" name="district" class="form-control" value="<?= h($form['district']) ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                           <label>Phường/Xã</label>
                           <input type="text" name="ward" class="form-control" value="<?= h($form['ward']) ?>" required>
                        </div>
                        <div class="form-group col-md-12">
                           <label>Địa chỉ cụ thể</label>
                           <textarea name="address_detail" class="form-control" rows="3" required><?= h($form['address_detail']) ?></textarea>
                        </div>
                        <div class="form-group col-md-12">
                           <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                              <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" <?= !empty($form['is_default']) ? 'checked' : '' ?>>
                              <label class="custom-control-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                           </div>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-primary mr-2">Lưu thay đổi</button>
                     <a href="profile.php" class="btn btn-danger">Hủy</a>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
