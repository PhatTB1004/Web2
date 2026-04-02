
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Địa chỉ giao hàng';
require_login('sign-in.php');
$user = current_user($conn);

$stmt = mysqli_prepare($conn, 'SELECT * FROM address WHERE user_id = ? ORDER BY is_default DESC, id DESC LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$address = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

$userId = (int) $user['id'];
$form = [
    'receiver_name' => $address['receiver_name'] ?? $user['fullname'],
    'phone' => $address['phone'] ?? $user['phone'],
    'address_detail' => $address['address_detail'] ?? '',
    'ward' => $address['ward'] ?? '',
    'district' => $address['district'] ?? '',
    'province' => $address['province'] ?? '',
    'is_default' => !empty($address['is_default']) ? 1 : 1,
];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['receiver_name'] = trim($_POST['receiver_name'] ?? '');
    $form['phone'] = trim($_POST['phone'] ?? '');
    $form['address_detail'] = trim($_POST['address_detail'] ?? '');
    $form['ward'] = trim($_POST['ward'] ?? '');
    $form['district'] = trim($_POST['district'] ?? '');
    $form['province'] = trim($_POST['province'] ?? '');
    $form['is_default'] = isset($_POST['is_default']) ? 1 : 0;

    if ($form['receiver_name'] === '' || $form['phone'] === '' || $form['address_detail'] === '' || $form['ward'] === '' || $form['district'] === '' || $form['province'] === '') {
        $error = 'Vui lòng nhập đầy đủ địa chỉ.';
    } elseif (!preg_match('/^[0-9]{9,11}$/', $form['phone'])) {
        $error = 'Số điện thoại không hợp lệ.';
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO address (user_id, receiver_name, phone, address_detail, ward, district, province, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE receiver_name = VALUES(receiver_name), phone = VALUES(phone), address_detail = VALUES(address_detail), ward = VALUES(ward), district = VALUES(district), province = VALUES(province), is_default = VALUES(is_default)');
        mysqli_stmt_bind_param($stmt, 'issssssi', $userId, $form['receiver_name'], $form['phone'], $form['address_detail'], $form['ward'], $form['district'], $form['province'], $form['is_default']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['checkout_address_saved'] = 1;
        header('Location: Checkout-preview.php');
        exit;
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="row">
         <div class="col-sm-12">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title"><h4 class="card-title">Địa chỉ của bạn</h4></div>
               </div>
               <div class="iq-card-body">
                  <?php if ($error): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
                  <form method="post" action="">
                     <div class="form-group"><label>Tên người nhận</label><input type="text" name="receiver_name" class="form-control" value="<?= h($form['receiver_name']) ?>" required></div>
                     <div class="form-group"><label>Số điện thoại</label><input type="text" name="phone" class="form-control" value="<?= h($form['phone']) ?>" pattern="[0-9]{9,11}" required></div>
                     <div class="form-group"><label>Địa chỉ</label><input type="text" name="address_detail" class="form-control" value="<?= h($form['address_detail']) ?>" required></div>
                     <div class="form-group"><label>Phường/Xã</label><input type="text" name="ward" class="form-control" value="<?= h($form['ward']) ?>" required></div>
                     <div class="form-group"><label>Quận/Huyện</label><input type="text" name="district" class="form-control" value="<?= h($form['district']) ?>" required></div>
                     <div class="form-group"><label>Tỉnh/Thành phố</label><input type="text" name="province" class="form-control" value="<?= h($form['province']) ?>" required></div>
                     <div class="form-group">
                        <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                           <input type="checkbox" class="custom-control-input" id="defaultAddress" name="is_default" <?= !empty($form['is_default']) ? 'checked' : '' ?>>
                           <label class="custom-control-label" for="defaultAddress">Đặt làm mặc định</label>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-primary">Lưu và tiếp tục</button>
                     <a href="Checkout.php" class="btn btn-danger">Trở lại</a>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>
