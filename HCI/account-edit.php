<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

<?php include "includes/topnav.php"; ?>

<?php include "includes/database.php"; ?>

<!-- Page Content  -->
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="iq-card">
                    <div class="iq-edit-list-data">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="edit-account" role="tabpanel">
                                <div class="iq-card">
                                    <div class="iq-card-header d-flex justify-content-between">
                                        <div class="iq-header-title">
                                            <h4 class="card-title">Sửa tài khoản</h4>
                                        </div>
                                    </div>
                                    <div class="iq-card-body">
                                        <form>
                                            <div class="row align-items-center">
                                                <div class="form-group col-sm-6">
                                                    <label for="account-name">Tên tài khoản:</label>
                                                    <input type="text" class="form-control" id="account-name"
                                                        value="PhatTB">
                                                </div>

                                                <div class="form-group col-sm-6">
                                                    <label for="account-email">Gmail:</label>
                                                    <input type="email" class="form-control" id="account-email"
                                                        value="email@gmail.com">
                                                </div>

                                                <div class="form-group col-sm-6">
                                                    <label for="current-password">Mật khẩu hiện tại:</label>
                                                    <input type="password" class="form-control" id="current-password"
                                                        placeholder="Nhập mật khẩu hiện tại">
                                                </div>

                                                <div class="form-group col-sm-6">
                                                    <label for="new-password">Mật khẩu mới:</label>
                                                    <input type="password" class="form-control" id="new-password"
                                                        placeholder="Nhập mật khẩu mới">
                                                </div>

                                                <div class="form-group col-sm-6">
                                                    <label for="confirm-password">Nhập lại mật khẩu mới:</label>
                                                    <input type="password" class="form-control" id="confirm-password"
                                                        placeholder="Xác nhận mật khẩu mới">
                                                </div>
                                            </div>

                                            <a href="profile.php" class="btn btn-primary mr-2">Lưu</a>
                                            <a href="profile.php" class="btn btn-danger mr-2">Hủy bỏ</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>