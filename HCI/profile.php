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
                    <div class="iq-card-body p-0">
                        <div class="iq-edit-list">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-4 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        Thông tin cá nhân
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#account-info">
                                        Tài khoản
                                    </a>
                                </li>
                                <li class="col-md-4 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#manage-contact">
                                        Quản lý liên hệ
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="iq-edit-list-data">
                    <div class="tab-content">

                        <!-- ================= THÔNG TIN CÁ NHÂN ================= -->
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Thông tin cá nhân</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <form>
                                        <div class="form-group row align-items-center">
                                            <div class="col-md-12">
                                                <div class="profile-img-edit">
                                                    <img class="profile-pic" src="images/user/1.jpg" alt="profile-pic">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="form-group col-sm-6">
                                                <label>Họ:</label> Đào Thiện
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Tên:</label> Phát
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Giới tính:</label> Nam
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Ngày sinh:</label> 10/04/2006
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Quốc gia:</label> Việt Nam
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Tỉnh/Thành phố:</label> Hồ Chí Minh
                                            </div>
                                        </div>

                                        <a href="profile-edit.php" class="btn btn-primary mr-2">Chỉnh sửa</a>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- ================= TÀI KHOẢN ================= -->
                        <div class="tab-pane fade" id="account-info" role="tabpanel">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Tài khoản</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <form>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label>Tên tài khoản</label>
                                                <input type="text" class="form-control" value="PhatTB" readonly>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label>Gmail</label>
                                                <input type="email" class="form-control" value="email@gmail.com"
                                                    readonly>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label>Mật khẩu</label>
                                                <input type="password" class="form-control" value="********" readonly>
                                            </div>
                                        </div>

                                        <a href="account-edit.php" class="btn btn-primary mr-2">Sửa</a>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- ================= QUẢN LÝ LIÊN HỆ ================= -->
                        <div class="tab-pane fade" id="manage-contact" role="tabpanel">
                            <div class="iq-card">
                                <div class="iq-card-header">
                                    <h4 class="card-title">Quản lý liên hệ</h4>
                                </div>
                                <div class="iq-card-body">
                                    <form>

                                        <!-- SĐT -->
                                        <div class="form-group">
                                            <label>Số điện thoại</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="09******20" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-primary">Sửa</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Địa chỉ -->
                                        <div class="form-group">
                                            <div class="d-flex justify-content-between mb-2">
                                                <label>Các địa chỉ đang có</label>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#addAddressModal">
                                                    Thêm địa chỉ
                                                </button>
                                            </div>

                                            <div class="list-group">

                                                <div class="list-group-item d-flex justify-content-between">
                                                    <div>
                                                        <strong>Địa chỉ 1</strong><br>
                                                        10/41A Âu Dương Lân, Quận 8, Hồ Chí Minh
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#editAddressModal">
                                                            Sửa
                                                        </button>
                                                        <button class="btn btn-sm btn-danger">Xóa</button>
                                                    </div>
                                                </div>

                                                <div class="list-group-item d-flex justify-content-between">
                                                    <div>
                                                        <strong>Địa chỉ 2</strong><br>
                                                        123 Nguyễn Văn Cừ, Quận 5, Hồ Chí Minh
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#editAddressModal">
                                                            Sửa
                                                        </button>
                                                        <button class="btn btn-sm btn-danger">Xóa</button>
                                                    </div>
                                                </div>

                                            </div>
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
</div>
<!-- Modal thêm địa chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Họ và tên người nhận</label>
                            <input type="text" class="form-control" placeholder="Nhập họ tên">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Số điện thoại</label>
                            <input type="text" class="form-control" placeholder="Nhập số điện thoại">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tỉnh/Thành phố</label>
                            <input type="text" class="form-control" placeholder="Nhập tỉnh/thành phố">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Quận/Huyện</label>
                            <input type="text" class="form-control" placeholder="Nhập quận/huyện">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Phường/Xã</label>
                            <input type="text" class="form-control" placeholder="Nhập phường/xã">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Địa chỉ cụ thể</label>
                            <textarea class="form-control" rows="3"
                                placeholder="Số nhà, tên đường, tòa nhà..."></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Ghi chú</label>
                            <textarea class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Lưu địa chỉ</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa địa chỉ -->
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">Sửa địa chỉ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Họ và tên người nhận</label>
                            <input type="text" class="form-control" value="Đào Thiện Phát">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Số điện thoại</label>
                            <input type="text" class="form-control" value="09******20">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tỉnh/Thành phố</label>
                            <input type="text" class="form-control" value="Hồ Chí Minh">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Quận/Huyện</label>
                            <input type="text" class="form-control" value="Quận 8">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Phường/Xã</label>
                            <input type="text" class="form-control" value="Phường 5">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Địa chỉ cụ thể</label>
                            <textarea class="form-control" rows="3">10/41A Âu Dương Lân, Quận 8, Hồ Chí Minh</textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Ghi chú</label>
                            <textarea class="form-control" rows="2">Giao giờ hành chính</textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Cập nhật</button>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>