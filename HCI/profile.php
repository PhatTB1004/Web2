<?php
require_once 'includes/app.php';
require_login();

$pageTitle = 'Tài khoản của tôi';
$pageBreadcrumb = 'Tài khoản của tôi';

$user = current_user();
$profile = fetch_one('SELECT * FROM users WHERE id = ' . (int) $user['id'] . ' LIMIT 1');

$activeTab = $_GET['tab'] ?? 'personal-information';
if (!in_array($activeTab, ['personal-information', 'account-info', 'manage-contact'], true)) {
    $activeTab = 'personal-information';
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
    $activeTab = 'manage-contact';

    $receiverName  = trim((string) ($_POST['receiver_name'] ?? ''));
    $phone         = trim((string) ($_POST['phone'] ?? ''));
    $addressDetail = trim((string) ($_POST['address_detail'] ?? ''));
    $ward          = trim((string) ($_POST['ward'] ?? ''));
    $district      = trim((string) ($_POST['district'] ?? ''));
    $province      = trim((string) ($_POST['province'] ?? ''));
    $isDefault     = isset($_POST['is_default']) ? 1 : 0;

    if ($receiverName === '' || $phone === '' || $addressDetail === '' || $ward === '' || $district === '' || $province === '') {
        $error = 'Vui lòng nhập đầy đủ địa chỉ.';
    } else {
        if ($isDefault) {
            mysqli_query(db(), 'UPDATE address SET is_default = 0 WHERE user_id = ' . (int) $user['id']);
        }

        $stmt = mysqli_prepare(db(), '
            INSERT INTO address 
            (user_id, receiver_name, phone, address_detail, ward, district, province, is_default) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');

        mysqli_stmt_bind_param(
            $stmt,
            'issssssi',
            $user['id'],
            $receiverName,
            $phone,
            $addressDetail,
            $ward,
            $district,
            $province,
            $isDefault
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($ok) {
            flash('success', 'Đã lưu địa chỉ.');
            redirect('profile.php?tab=manage-contact');
        }

        $error = 'Không thể lưu địa chỉ.';
    }
}

$addresses = user_addresses((int) $user['id']);
$addressForm = $addresses[0] ?? [
    'receiver_name' => $profile['fullname'] ?? ($user['fullname'] ?? $user['username'] ?? ''),
    'phone' => $profile['phone'] ?? ($user['phone'] ?? ''),
    'address_detail' => '',
    'ward' => '',
    'district' => '',
    'province' => '',
    'is_default' => 1,
];

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topnav.php';
?>

<link rel="stylesheet" href="css/profile.css?v=1">

<div id="content-page" class="content-page">
   <div class="container-fluid profile-page">

      <?php render_flash(); ?>

      <div class="profile-hero">
         <h2>Tài khoản của tôi</h2>
         <p>Quản lý thông tin cá nhân, tài khoản và địa chỉ nhận hàng trong một nơi duy nhất.</p>
      </div>

      <div class="profile-shell">
         <div class="profile-tabs-wrap">
            <ul class="profile-tabs nav nav-pills w-100">
               <li class="tab-item, flex-fill">
                  <a class="nav-link <?php echo $activeTab === 'personal-information' ? 'active' : ''; ?>"
                     data-toggle="pill" href="#personal-information">Thông tin Tài khoản</a>
               </li>
               <li class="tab-item, flex-fill">
                  <a class="nav-link <?php echo $activeTab === 'manage-contact' ? 'active' : ''; ?>" data-toggle="pill"
                     href="#manage-contact">Quản lý liên hệ</a>
               </li>
            </ul>
         </div>

         <div class="tab-content">
            <div class="tab-pane fade <?php echo $activeTab === 'personal-information' ? 'active show' : ''; ?>"
               id="personal-information" role="tabpanel">
               <div class="profile-card">
                  <div class="profile-card-header d-flex justify-content-between align-items-center">
                     <div class="iq-header-title">
                        <h4 class="card-title">Thông tin cá nhân</h4>
                     </div>
                  </div>
                  <div class="profile-card-body">
                     <div class="info-grid">
                        <div class="info-item">
                           <label>Họ và tên</label>
                           <div class="value"><?php echo h($profile['fullname'] ?? ''); ?></div>
                        </div>

                        <div class="info-item">
                           <label>Email</label>
                           <div class="value"><?php echo h($profile['email'] ?? ''); ?></div>
                        </div>
                        <div class="info-item">
                           <label>Tên tài khoản</label>
                           <div class="value"><?php echo h($profile['username'] ?? ''); ?></div>
                        </div>
                        <div class="info-item">
                           <label>Số điện thoại</label>
                           <div class="value"><?php echo h($profile['phone'] ?? ''); ?></div>
                        </div>
                     </div>

                     <div class="mt-4">
                        <a href="account-edit.php?section=account-info" class="btn-soft btn-soft-primary">
                           Chỉnh sửa tài khoản
                        </a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="tab-pane fade <?php echo $activeTab === 'manage-contact' ? 'active show' : ''; ?>"
               id="manage-contact" role="tabpanel">
               <div class="profile-card">
                  <div class="profile-card-header">
                     <div class="section-toolbar mb-0">
                        <div>
                           <h4 class="card-title mb-1">Quản lý liên hệ</h4>
                           <div class="helper">Xem và quản lý địa chỉ nhận hàng của bạn.</div>
                        </div>

                        <div class="panel-actions">
                           <a href="add-address.php" class="btn-soft btn-soft-primary">
                              + Thêm địa chỉ mới
                           </a>
                        </div>
                     </div>
                  </div>

                  <div class="profile-card-body">
                     <?php if ($error): ?>
                     <div class="alert alert-danger alert-custom"><?php echo h($error); ?></div>
                     <?php endif; ?>

                     <div class="subtle-note">
                        Bạn có thể đặt một địa chỉ làm mặc định để thao tác thanh toán nhanh hơn.
                     </div>

                     <div class="address-list mb-3">
                        <?php if ($addresses): ?>
                        <?php foreach ($addresses as $address): ?>
                        <div class="address-item">
                           <div class="address-main">
                              <strong><?php echo h($address['receiver_name']); ?></strong><br>
                              <?php echo h($address['phone']); ?><br>
                              <?php echo h($address['address_detail'] . ', ' . $address['ward'] . ', ' . $address['district'] . ', ' . $address['province']); ?>
                           </div>

                           <div>
                              <?php if ((int) $address['is_default'] === 1): ?>
                              <span class="badge-default">Mặc định</span>
                              <?php endif; ?>
                              <a href="address-edit.php?id=<?php echo (int) $address['id']; ?>" class="btn-soft btn-soft-outline">
         Sửa
      </a>
                           </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="alert alert-info alert-custom mb-0">Chưa có địa chỉ nào.</div>
                        <?php endif; ?>
                     </div>

                     <form method="post" class="mt-4">
                        <input type="hidden" name="save_address" value="1">

                        <div class="custom-control custom-checkbox mb-3">
                           <input type="checkbox" class="custom-control-input" id="default-address" name="is_default"
                              value="1" <?php echo (int) ($addressForm['is_default'] ?? 0) === 1 ? 'checked' : ''; ?>>
                           <label class="custom-control-label" for="default-address">Đặt làm mặc định</label>
                        </div>

                        <a href="add-address.php" class="btn-soft btn-soft-outline">
                           + Thêm địa chỉ mới
                        </a>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'includes/footer.php'; ?>