
<?php
require_once __DIR__ . '/includes/bootstrap.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$page_title = 'Đăng ký';
$error = '';
$success = '';
$form = [
    'username' => '',
    'fullname' => '',
    'phone' => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['username'] = trim($_POST['username'] ?? '');
    $form['fullname'] = trim($_POST['fullname'] ?? '');
    $form['phone'] = trim($_POST['phone'] ?? '');
    $form['email'] = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    if ($form['username'] === '' || $form['fullname'] === '' || $form['phone'] === '' || $form['email'] === '' || $password === '' || $confirm === '') {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } elseif (!preg_match('/^[A-Za-z0-9]+$/', $form['username'])) {
        $error = 'Tên tài khoản chỉ được dùng chữ cái và số, không có ký tự đặc biệt.';
    } elseif (!preg_match('/^[A-Za-z0-9._%+-]+@gmail\.com$/i', $form['email'])) {
        $error = 'Email phải có định dạng @gmail.com.';
    } elseif (!preg_match('/^[0-9]{9,11}$/', $form['phone'])) {
        $error = 'Số điện thoại phải có 9 đến 11 chữ số.';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif ($password !== $confirm) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'ss', $form['username'], $form['email']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $exists = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        if ($exists) {
            $error = 'Tên tài khoản hoặc email đã tồn tại.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'customer';
            $status = 'active';
            $empty = '';
            $stmt = mysqli_prepare($conn, 'INSERT INTO users (username, password, fullname, phone, email, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sssssss', $form['username'], $hash, $form['fullname'], $form['phone'], $form['email'], $role, $status);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $success = 'Đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.';
            $form = ['username' => '', 'fullname' => '', 'phone' => '', 'email' => ''];
        }
    }
}

include 'includes/header.php';
?>

<section class="sign-in-page">
    <div class="container p-0">
        <div class="row no-gutters height-self-center">
            <div class="col-sm-12 align-self-center page-content rounded">
                <div class="row m-0">
                    <div class="col-sm-12 sign-in-page-data">
                        <div class="sign-in-from bg-primary rounded">
                            <h3 class="mb-0 text-center text-white">Đăng ký</h3>
                            <p class="text-center text-white">Tạo tài khoản mới để mua sách và theo dõi đơn hàng.</p>
                            <?php if ($error): ?>
                                <div class="alert alert-danger mb-3"><?= h($error) ?></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success mb-3"><?= h($success) ?></div>
                            <?php endif; ?>
                            <form class="mt-4 form-text" method="post" action="">
                                <div class="form-group">
                                    <label for="username">Tên tài khoản</label>
                                    <input type="text" name="username" class="form-control mb-0" id="username" placeholder="Nhập tên tài khoản" value="<?= h($form['username']) ?>" pattern="[A-Za-z0-9]+" title="Chỉ dùng chữ cái và số" required>
                                </div>
                                <div class="form-group">
                                    <label for="fullname">Họ và tên</label>
                                    <input type="text" name="fullname" class="form-control mb-0" id="fullname" placeholder="Nhập họ và tên" value="<?= h($form['fullname']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control mb-0" id="phone" placeholder="Nhập số điện thoại" value="<?= h($form['phone']) ?>" pattern="[0-9]{9,11}" title="Từ 9 đến 11 chữ số" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control mb-0" id="email" placeholder="Nhập email @gmail.com" value="<?= h($form['email']) ?>" pattern="[A-Za-z0-9._%+-]+@gmail\.com" title="Email phải có đuôi @gmail.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control mb-0" id="password" placeholder="Mật khẩu" minlength="6" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Nhập lại mật khẩu</label>
                                    <input type="password" name="confirm_password" class="form-control mb-0" id="confirm_password" placeholder="Xác nhận mật khẩu" minlength="6" required>
                                </div>
                                <div class="d-inline-block w-100">
                                    <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                        <input type="checkbox" class="custom-control-input" id="agree" required>
                                        <label class="custom-control-label" for="agree">Tôi đồng ý với các điều khoản</label>
                                    </div>
                                </div>
                                <div class="sign-info text-center">
                                    <button type="submit" class="btn btn-white d-block w-100 mb-2">Đăng ký</button>
                                    <span class="text-dark d-inline-block line-height-2">Đã có tài khoản?<a href="sign-in.php" class="text-white"> Đăng nhập</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
