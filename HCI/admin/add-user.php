<?php
$page_title = 'Thêm người dùng';
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'customer';
    $status = $_POST['status'] ?? 'active';
    $receiverName = trim($_POST['receiver_name'] ?? '');
    $addrPhone = trim($_POST['addr_phone'] ?? '');
    $addressDetail = trim($_POST['address_detail'] ?? '');
    $ward = trim($_POST['ward'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $province = trim($_POST['province'] ?? '');

    if ($username === '' || $fullname === '' || $email === '' || $password === '') {
        flash('danger', 'Vui lòng nhập đầy đủ thông tin bắt buộc.');
        redirect('add-user.php');
    }

    if ($role === 'customer' && ($receiverName === '' || $addressDetail === '' || $ward === '' || $district === '' || $province === '')) {
        flash('danger', 'Khách hàng phải có địa chỉ giao hàng mặc định đầy đủ.');
        redirect('add-user.php');
    }

    $exists = fetch_one("SELECT id FROM users WHERE username = '" . mysqli_real_escape_string(db(), $username) . "' OR email = '" . mysqli_real_escape_string(db(), $email) . "' LIMIT 1");
    if ($exists) {
        flash('danger', 'Tên đăng nhập hoặc email đã tồn tại.');
        redirect('add-user.php');
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare(db(), 'INSERT INTO users (username, password, fullname, phone, email, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sssssss', $username, $hash, $fullname, $phone, $email, $role, $status);
    mysqli_stmt_execute($stmt);
    $userId = mysqli_insert_id(db());
    mysqli_stmt_close($stmt);

    if ($role === 'customer') {
        $isDefault = 1;
        $stmt = mysqli_prepare(db(), 'INSERT INTO address (user_id, receiver_name, phone, address_detail, ward, district, province, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'issssssi', $userId, $receiverName, $addrPhone, $addressDetail, $ward, $district, $province, $isDefault);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    flash('success', 'Đã thêm tài khoản mới.');
    redirect('user.php');
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
?>
<div id="content-page" class="content-page">
   <div class="container-fluid">
      <div class="iq-card">
         <div class="iq-card-header"><h4 class="card-title mb-0">Thêm tài khoản</h4></div>
         <div class="iq-card-body">
            <form method="post">
               <div class="row">
                  <div class="col-md-6 form-group"><label>Tên đăng nhập</label><input name="username" class="form-control" required></div>
                  <div class="col-md-6 form-group"><label>Họ tên</label><input name="fullname" class="form-control" required></div>
                  <div class="col-md-6 form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                  <div class="col-md-6 form-group"><label>SĐT</label><input name="phone" class="form-control"></div>
                  <div class="col-md-6 form-group"><label>Mật khẩu</label><input type="password" name="password" class="form-control" required></div>
                  <div class="col-md-3 form-group"><label>Vai trò</label><select name="role" class="form-control"><option value="customer">customer</option><option value="admin">admin</option></select></div>
                  <div class="col-md-3 form-group"><label>Trạng thái</label><select name="status" class="form-control"><option value="active">active</option><option value="locked">locked</option></select></div>
               </div>
               <hr>
               <h5>Địa chỉ mặc định (bắt buộc cho khách hàng)</h5>
               <div class="row">
                  <div class="col-md-6 form-group"><label>Tên người nhận</label><input name="receiver_name" class="form-control"></div>
                  <div class="col-md-6 form-group"><label>SĐT nhận hàng</label><input name="addr_phone" class="form-control"></div>
                  <div class="col-md-12 form-group"><label>Địa chỉ chi tiết</label><input name="address_detail" class="form-control"></div>
                  <div class="col-md-4 form-group"><label>Phường</label><input name="ward" class="form-control"></div>
                  <div class="col-md-4 form-group"><label>Quận/Huyện</label><input name="district" class="form-control"></div>
                  <div class="col-md-4 form-group"><label>Tỉnh/Thành</label><input name="province" class="form-control"></div>
               </div>
               <button class="btn btn-primary">Lưu</button> <a href="user.php" class="btn btn-secondary">Quay lại</a>
            </form>
         </div>
      </div>
   </div>
</div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
