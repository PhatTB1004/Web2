<?php
require_once 'includes/app.php';
require_login();

$pageTitle = 'Sửa địa chỉ';
$pageBreadcrumb = 'Sửa địa chỉ';

$user = current_user();
$addressId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($addressId <= 0) {
    flash('error', 'Địa chỉ không hợp lệ.');
    redirect('profile.php?tab=manage-contact');
}

$address = fetch_one(
    'SELECT * FROM address WHERE id = ' . $addressId . ' AND user_id = ' . (int) $user['id'] . ' LIMIT 1'
);

if (!$address) {
    flash('error', 'Không tìm thấy địa chỉ cần sửa.');
    redirect('profile.php?tab=manage-contact');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $receiverName  = trim((string) ($_POST['receiver_name'] ?? ''));
    $phone         = trim((string) ($_POST['phone'] ?? ''));
    $addressDetail = trim((string) ($_POST['address_detail'] ?? ''));
    $ward          = trim((string) ($_POST['ward'] ?? ''));
    $district      = trim((string) ($_POST['district'] ?? ''));
    $province      = trim((string) ($_POST['province'] ?? ''));
    $isDefault     = isset($_POST['is_default']) ? 1 : 0;

    if ($receiverName === '' || $phone === '' || $addressDetail === '' || $ward === '' || $district === '' || $province === '') {
    $error = 'Vui lòng nhập đầy đủ thông tin địa chỉ.';
}

// Họ tên: chỉ chữ + khoảng trắng (có hỗ trợ tiếng Việt)
elseif (!preg_match('/^[A-Za-zÀ-ỹ\s]+$/u', $receiverName)) {
    $error = 'Tên người nhận chỉ được chứa chữ cái và khoảng trắng.';
}

// SĐT: 10 số, bắt đầu bằng 0
elseif (!preg_match('/^0\d{9}$/', $phone)) {
    $error = 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0.';
}

// Địa chỉ chi tiết
elseif (!preg_match('/^[A-Za-z0-9À-ỹ\s\/,.-]+$/u', $addressDetail)) {
    $error = 'Địa chỉ chi tiết không hợp lệ.';
}

// Phường / Xã
elseif (!preg_match('/^[A-Za-zÀ-ỹ0-9\s.-]+$/u', $ward)) {
    $error = 'Phường/Xã không hợp lệ.';
}

// Quận / Huyện
elseif (!preg_match('/^[A-Za-zÀ-ỹ0-9\s.-]+$/u', $district)) {
    $error = 'Quận/Huyện không hợp lệ.';
}

// Tỉnh / Thành phố
elseif (!preg_match('/^[A-Za-zÀ-ỹ\s.-]+$/u', $province)) {
    $error = 'Tỉnh/Thành phố không hợp lệ.';
}
  else {
        mysqli_begin_transaction(db());

        try {
            if ($isDefault) {
                $stmt = mysqli_prepare(db(), 'UPDATE address SET is_default = 0 WHERE user_id = ?');
                mysqli_stmt_bind_param($stmt, 'i', $user['id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }

            $stmt = mysqli_prepare(db(), '
                UPDATE address
                SET receiver_name = ?, phone = ?, address_detail = ?, ward = ?, district = ?, province = ?, is_default = ?
                WHERE id = ? AND user_id = ?
            ');

            mysqli_stmt_bind_param(
                $stmt,
                'ssssssiii',
                $receiverName,
                $phone,
                $addressDetail,
                $ward,
                $district,
                $province,
                $isDefault,
                $addressId,
                $user['id']
            );

            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if (!$ok) {
                throw new Exception('Update failed');
            }

            mysqli_commit(db());
            flash('success', 'Đã cập nhật địa chỉ.');
            redirect('profile.php?tab=manage-contact');
        } catch (Throwable $e) {
            mysqli_rollback(db());
            $error = 'Không thể cập nhật địa chỉ.';
        }
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<link rel="stylesheet" href="css/profile.css?v=1">

<div id="content-page" class="content-page">
   <div class="container-fluid profile-page">

      <?php render_flash(); ?>

      <div class="profile-hero">
         <h2>Sửa địa chỉ</h2>
         <p>Cập nhật thông tin địa chỉ nhận hàng của bạn.</p>
      </div>

      <div class="profile-shell">
         <div class="profile-card">
            <div class="profile-card-header">
               <div class="section-toolbar mb-0">
                  <div>
                     <h4 class="card-title mb-1">Thông tin địa chỉ</h4>
                     <div class="helper">Chỉnh sửa địa chỉ đã lưu.</div>
                  </div>

                  <div class="panel-actions">
                     <a href="profile.php?tab=manage-contact" class="btn-soft btn-soft-outline">
                        Quay lại
                     </a>
                  </div>
               </div>
            </div>

            <div class="profile-card-body">
               <?php if ($error): ?>
               <div class="alert alert-danger alert-custom"><?php echo h($error); ?></div>
               <?php endif; ?>

               <form method="post">
                  <input type="hidden" name="id" value="<?php echo (int) $address['id']; ?>">
                  <input type="hidden" name="update_address" value="1">

                  <div class="form-group">
                     <label for="receiver_name">Tên người nhận</label>
                     <input type="text" class="form-control" id="receiver_name" name="receiver_name"
                        value="<?php echo h($address['receiver_name'] ?? ''); ?>">
                  </div>

                  <div class="form-group">
                     <label for="phone">Số điện thoại</label>
                     <input type="text" class="form-control" id="phone" name="phone"
                        value="<?php echo h($address['phone'] ?? ''); ?>">
                  </div>

                  <div class="form-group">
                     <label for="address_detail">Địa chỉ chi tiết</label>
                     <input type="text" class="form-control" id="address_detail" name="address_detail"
                        value="<?php echo h($address['address_detail'] ?? ''); ?>">
                  </div>

                  <div class="form-group">
                     <label for="ward">Phường / Xã</label>
                     <input type="text" class="form-control" id="ward" name="ward"
                        value="<?php echo h($address['ward'] ?? ''); ?>">
                  </div>

                  <div class="form-group">
                     <label for="district">Quận / Huyện</label>
                     <input type="text" class="form-control" id="district" name="district"
                        value="<?php echo h($address['district'] ?? ''); ?>">
                  </div>

                  <div class="form-group">
                     <label for="province">Tỉnh / Thành phố</label>
                     <input type="text" class="form-control" id="province" name="province"
                        value="<?php echo h($address['province'] ?? ''); ?>">
                  </div>

                  <div class="custom-control custom-checkbox mb-3">
                     <input type="checkbox" class="custom-control-input" id="is_default" name="is_default" value="1"
                        <?php echo (int) ($address['is_default'] ?? 0) === 1 ? 'checked' : ''; ?>>
                     <label class="custom-control-label" for="is_default">Đặt làm mặc định</label>
                  </div>

                  <button type="submit" class="btn-soft btn-soft-primary">
                     Lưu thay đổi
                  </button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>