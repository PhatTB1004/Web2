<?php
require_once 'includes/app.php';
require_login();

$pageTitle = 'Sửa thông tin tài khoản';
$pageBreadcrumb = 'Sửa thông tin tài khoản';

$user = current_user();
$profile = fetch_one('SELECT * FROM users WHERE id = ' . (int)$user['id'] . ' LIMIT 1');
$avatarPath = $profile['image'] ?? 'images/user/00.jpg';
if ($avatarPath === '' || $avatarPath === null) {
    $avatarPath = 'images/user/00.jpg';
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim((string)($_POST['username'] ?? ''));
    $email      = trim((string)($_POST['email'] ?? ''));
    $fullname   = trim((string)($_POST['fullname'] ?? ''));
    $phone      = trim((string)($_POST['phone'] ?? ''));

    $currentPassword = (string)($_POST['current_password'] ?? '');
    $newPassword     = (string)($_POST['new_password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    $changePassword = !empty($newPassword);
    
    $avatarPath = $profile['image'] ?? 'images/user/00.jpg';
    if ($avatarPath === '' || $avatarPath === null) {
        $avatarPath = 'images/user/00.jpg';
    }
    $avatarChanged = isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0;

    // ================= VALIDATION =================
    if ($username === '' || $email === '' || $fullname === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin bắt buộc.';
    } 
    elseif (!username_valid($username)) {
        $error = 'Tên tài khoản không hợp lệ.';
    } 
    elseif (!gmail_valid($email)) {
        $error = 'Email phải có đuôi @gmail.com.';
    } 
    elseif ($changePassword && empty($currentPassword)) {
        $error = 'Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu.';
    } 
    elseif ($changePassword && !password_matches($currentPassword, (string)$profile['password'])) {
        $error = 'Mật khẩu hiện tại không đúng.';
    } 
    elseif ($changePassword && strlen($newPassword) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } 
    elseif ($changePassword && $newPassword !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } 
    elseif (!preg_match('/^[A-Za-zÀ-ỹ\s]+$/u', $fullname)) {
        $error = 'Họ tên chỉ được chứa chữ cái và khoảng trắng.';
    }
    // Phone: 10 số, bắt đầu bằng 0
    elseif ($phone !== '' && !preg_match('/^0\d{9}$/', $phone)) {
        $error = 'Số điện thoại phải gồm 10 chữ số và bắt đầu bằng 0.';
    }
    else {
    // ===== XỬ LÝ AVATAR TRƯỚC =====
    $avatarToSave = $profile['image'] ?? 'images/user/00.jpg';

    $uploadedAvatar = upload_file('avatar', 'images/user', $avatarToSave);

    if ($uploadedAvatar !== null) {
        $avatarToSave = $uploadedAvatar;
    }

    // ===== CHECK CÓ THAY ĐỔI KHÔNG =====
    $hasChange = 
        $username !== ($profile['username'] ?? '') ||
        $email    !== ($profile['email'] ?? '') ||
        $fullname !== ($profile['fullname'] ?? '') ||
        $phone    !== ($profile['phone'] ?? '') ||
        $changePassword ||
        $avatarToSave !== ($profile['image'] ?? 'images/user/00.jpg');

    if (!$hasChange) {
        $error = 'Bạn chưa thay đổi thông tin nào.';
    } else {
        // Kiểm tra trùng
        $exists = fetch_one("SELECT id FROM users 
                             WHERE (username = '" . esc($username) . "' 
                                OR email = '" . esc($email) . "') 
                               AND id <> " . (int)$user['id'] . " LIMIT 1");

        if ($exists) {
            $error = 'Tên tài khoản hoặc email đã tồn tại bởi người dùng khác.';
        } else {
            // password
            $newHash = $changePassword 
                ? password_hash($newPassword, PASSWORD_DEFAULT) 
                : $profile['password'];

            // update DB
            $stmt = mysqli_prepare(db(), 
                'UPDATE users SET username=?, email=?, fullname=?, phone=?, image=?, password=? WHERE id=?'
            );
            mysqli_stmt_bind_param($stmt, 'ssssssi', $username, $email, $fullname, $phone, $avatarToSave, $newHash, $user['id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // session
            $_SESSION['user'] = [
                'id'       => (int)$user['id'],
                'username' => $username,
                'fullname' => $fullname,
                'email'    => $email,
                'phone'    => $phone,
                'image'    => $avatarToSave,
                'role'     => $user['role'],
            ];

            flash('success', 'Cập nhật thông tin tài khoản thành công!');
            redirect('profile.php?tab=personal-information');
            }
        }
    }
}

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<link rel="stylesheet" href="css/account-edit.css?v=1">

<div id="content-page" class="content-page">
    <?php render_flash(); ?>

    <div class="container-fluid edit-profile-page">
        <div class="edit-profile-hero">
            <h2>Sửa thông tin tài khoản</h2>
            <p>Cập nhật thông tin cá nhân, số điện thoại và mật khẩu ngay trong một trang duy nhất.</p>
        </div>

        <div class="edit-shell">
            <div class="edit-shell-header">
                <h4 class="edit-title mb-0">Chỉnh sửa hồ sơ</h4>
                <div class="edit-subtitle">Điền thông tin mới rồi lưu thay đổi để cập nhật tài khoản.</div>
            </div>

            <div class="edit-shell-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-custom"><?php echo h($error); ?></div>
                        <?php endif; ?>

                        <div class="edit-card">
                            <div class="edit-card-header d-flex justify-content-between align-items-center flex-wrap">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Thông tin tài khoản</h4>
                                </div>
                            </div>

                            <div class="edit-card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="section-box">
                                        <h5>Thông tin cá nhân</h5>
                                        <div class="section-desc">
                                            Cập nhật tên tài khoản, email, họ tên và số điện thoại để đồng bộ với hồ sơ
                                            của bạn.
                                        </div>

                                        <div class="profile-grid">
                                            <div class="field-card">
                                                <label for="username">Tên tài khoản <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="username" class="form-control" id="username"
                                                    value="<?php echo h($profile['username'] ?? ''); ?>" required>
                                            </div>

                                            <div class="field-card">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    value="<?php echo h($profile['email'] ?? ''); ?>" required>
                                            </div>

                                            <div class="field-card">
                                                <label for="fullname">Họ và tên <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="fullname" class="form-control" id="fullname"
                                                    value="<?php echo h($profile['fullname'] ?? ''); ?>" required>
                                            </div>

                                            <div class="field-card">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="text" name="phone" class="form-control" id="phone"
                                                    value="<?php echo h($profile['phone'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-box">
                                        <h5>Ảnh đại diện</h5>
                                        <div class="section-desc">
                                            Chọn ảnh đại diện mới để cập nhật hồ sơ của bạn. Nếu không chọn ảnh mới, ảnh
                                            hiện tại sẽ được giữ nguyên.
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-3 mb-3 mb-md-0">
                                                <img src="<?php echo h($avatarPath); ?>" alt="Avatar"
                                                    style="width:120px;height:120px;object-fit:cover;border-radius:18px;border:1px solid #e2e8f0;background:#fff;">
                                            </div>
                                            <div class="col-md-9">
                                                <div class="field-card">
                                                    <label for="avatar">Tải lên ảnh mới</label>
                                                    <input type="file" name="avatar" id="avatar" class="form-control"
                                                        accept="image/*">
                                                    <small class="help-text">Ảnh nên ở định dạng JPG, PNG hoặc
                                                        WEBP.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-box">
                                        <h5>Đổi mật khẩu</h5>
                                        <div class="password-note">
                                            Để trống nếu bạn không muốn thay đổi mật khẩu. Khi đổi mật khẩu, hãy nhập
                                            đúng mật khẩu hiện tại.
                                        </div>

                                        <div class="password-grid">
                                            <div class="field-card">
                                                <label for="current_password">Mật khẩu hiện tại</label>
                                                <input type="password" name="current_password" class="form-control"
                                                    id="current_password" placeholder="Nhập nếu muốn đổi mật khẩu">
                                            </div>

                                            <div class="field-card">
                                                <label for="new_password">Mật khẩu mới</label>
                                                <input type="password" name="new_password" class="form-control"
                                                    id="new_password" placeholder="Ít nhất 6 ký tự">
                                            </div>

                                            <div class="field-card">
                                                <label for="confirm_password">Nhập lại mật khẩu mới</label>
                                                <input type="password" name="confirm_password" class="form-control"
                                                    id="confirm_password" placeholder="Xác nhận mật khẩu mới">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-bar">
                                        <button type="submit" class="btn-soft btn-soft-primary">Lưu thay đổi</button>
                                        <a href="profile.php" class="btn-soft btn-soft-secondary">Hủy bỏ</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>