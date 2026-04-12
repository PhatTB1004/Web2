<?php
$admin_public_page = true;
require_once __DIR__ . '/includes/bootstrap.php';

if (is_admin_logged_in()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $loginEsc = mysqli_real_escape_string(db(), $login);
    $user = fetch_one("SELECT * FROM users WHERE (username = '{$loginEsc}' OR email = '{$loginEsc}') AND role = 'admin' LIMIT 1");

    if (!$user) {
        $error = 'Tài khoản quản trị không tồn tại.';
    } elseif ($user['status'] === 'locked') {
        $error = 'Tài khoản đã bị khoá.';
    } elseif (!password_verify($password, $user['password'])) {
        $error = 'Mật khẩu không chính xác.';
    } else {
        $_SESSION['admin'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'fullname' => $user['fullname'] ?? $user['full_name'] ?? '',
            'full_name' => $user['fullname'] ?? $user['full_name'] ?? '',
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        flash('success', 'Đăng nhập thành công.');
        redirect('dashboard.php');
    }
}

$page_title = 'Đăng nhập quản trị';

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo h($page_title); ?></title>
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/typography.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/line-awesome.min.css">
    <link rel="stylesheet" href="../css/ionicons.min.css">
    <link rel="stylesheet" href="../css/remixicon.css">
</head>
<section class="sign-in-page">
   <div class="container p-0">
      <div class="row no-gutters height-self-center">
         <div class="col-sm-12 align-self-center page-content rounded">
            <div class="row m-0">
               <div class="col-sm-12 sign-in-page-data">
                  <div class="sign-in-from bg-primary rounded">
                     <h3 class="mb-0 text-center text-white">Đăng nhập quản trị</h3>
                     <?php if ($error): ?><div class="alert alert-light"><?php echo h($error); ?></div><?php endif; ?>
                     <form class="mt-4 form-text" method="post" autocomplete="off">
                        <div class="form-group">
                           <label class="text-white" for="login">Email hoặc tên đăng nhập:</label>
                           <input type="text" name="login" id="login" class="form-control mb-0" required>
                        </div>
                        <div class="form-group">
                           <label class="text-white" for="password">Mật khẩu:</label>
                           <input type="password" name="password" id="password" class="form-control mb-0" required>
                        </div>
                        <div class="sign-info text-center">
                           <button type="submit" class="btn btn-white d-block w-100 mb-2">Đăng nhập</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
