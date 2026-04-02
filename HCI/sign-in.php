
<?php
require_once __DIR__ . '/includes/bootstrap.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$page_title = 'Đăng nhập';
$error = '';
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($identifier === '' || $password === '') {
        $error = 'Vui lòng nhập tên tài khoản/email và mật khẩu.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, username, password, fullname, phone, email, role, status FROM users WHERE username = ? OR email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'ss', $identifier, $identifier);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        if (!$user) {
            $error = 'Tài khoản không tồn tại.';
        } elseif ($user['status'] !== 'active') {
            $error = 'Tài khoản đang bị khóa.';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Mật khẩu không đúng.';
        } else {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['user_cache'] = $user;

            $next = $_GET['next'] ?? 'index.php';
            if (!preg_match('#^[A-Za-z0-9_./?=&%-]+$#', $next)) {
                $next = 'index.php';
            }
            header('Location: ' . $next);
            exit;
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
                            <h3 class="mb-0 text-center text-white">Đăng nhập</h3>
                            <p class="text-center text-white">Nhập email hoặc tên tài khoản và mật khẩu.</p>
                            <?php if ($error): ?>
                                <div class="alert alert-danger mb-3"><?= h($error) ?></div>
                            <?php endif; ?>
                            <form class="mt-4 form-text" method="post" action="">
                                <div class="form-group">
                                    <label for="identifier">Email hoặc tên tài khoản:</label>
                                    <input type="text" name="identifier" class="form-control mb-0" id="identifier" placeholder="Nhập email hoặc tên tài khoản" value="<?= h($identifier) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control mb-0" id="password" placeholder="Nhập mật khẩu" required>
                                    <a href="#" class="float-right text-dark">Quên mật khẩu?</a>
                                </div>
                                <div class="d-inline-block w-100">
                                    <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                        <input type="checkbox" class="custom-control-input" id="remember">
                                        <label class="custom-control-label" for="remember">Ghi nhớ</label>
                                    </div>
                                </div>
                                <div class="sign-info text-center">
                                    <button type="submit" class="btn btn-white d-block w-100 mb-2">Đăng nhập</button>
                                    <span class="text-dark dark-color d-inline-block line-height-2">Không có tài khoản?<a href="sign-up.php" class="text-white"> Đăng ký</a></span>
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
