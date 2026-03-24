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
                                            <a class="nav-link active"  href="#">
                                                Thông tin cá nhân
                                            </a>
                                        </li>
                                        <li class="col-md-4 p-0">
                                            <a class="nav-link"  href="#">
                                                Email và SMS
                                            </a>
                                        </li>
                                        <li class="col-md-4 p-0">
                                            <a class="nav-link"  href="#">
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
                                                            <img class="profile-pic" src="images/user/1.jpg"
                                                                alt="profile-pic">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class=" row align-items-center">
                                                    <div class="form-group col-sm-6">
                                                        <label for="fname">Họ: </label> Đào Thiện
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="lname">Tên: </label> Phát
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="uname">Tên tài khoản: </label> User
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label class="d">Giới tính: </label> Nam
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label for="dob">Ngày sinh: </label> 10/04/2006
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label>Quốc gia: </label> Việt Nam
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label>Tỉnh/Thành phố: </label> Hồ Chí Minh
                                                    </div>
                                                    <div class="form-group col-sm-12">
                                                        <label>Địa chỉ:</label>
                                                        10/41A Âu Dương Lân
                                                        Quận 8, Hồ Chí Minh
                                                        Việt Nam
                                                    </div>
                                                </div>
                                                <a href="profile-edit.php" class="btn btn-primary mr-2">Chỉnh sửa </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                                    <div class="iq-card">
                                        <div class="iq-card-header d-flex justify-content-between">
                                            <div class="iq-header-title">
                                                <h4 class="card-title">Đơn hàng</h4>
                                            </div>
                                        </div>
                                        <div class="iq-card-body">
                                            <form>
                                                <div class="form-group">
                                                    <label>Vừa mới đây <i class="ri-arrow-right-s-line"></i></label>
                                                    <li><a href="profile.php">
                                                            <div class="media align-items-center">
                                                                <div class="col-sm-1">

                                                                    <img width="90" height="90"
                                                                        class="img-fluid rounded"
                                                                        src="images/checkout/01.jpg" alt="">
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <h6>Economix - Các Nền Kinh Tế Vận Hành</h6>
                                                                    <p>x1</p>
                                                                    <h6>2 sản phẩm khác <i
                                                                            class="ri-arrow-down-s-line"></i></h6>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <p>Tổng tiền (3 sản phẩm): <label> 327.900đ</label>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <p class="text-warning">Đang giao hàng
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <hr>
                                                    <label>2 ngày trước <i class="ri-arrow-right-s-line"></i></label>
                                                    <li><a href="profile.php">
                                                            <div class="media align-items-center">
                                                                <div class="col-sm-1">

                                                                    <img width="90" height="90"
                                                                        class="img-fluid rounded"
                                                                        src="images/checkout/02.jpg" alt="">
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <h6>Người Bán Hàng Vĩ Đại Nhất Thế Giới</h6>
                                                                    <p>x1</p>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <p>Tổng tiền (1 sản phẩm): <label> 79.000đ</label>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <p class="text-primary">Đã hoàn tất giao đơn hàng
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <hr>
                                                    <label>3 ngày trước <i class="ri-arrow-right-s-line"></i></label>
                                                    <li><a href="profile.php">
                                                            <div class="media align-items-center">
                                                                <div class="col-sm-1">

                                                                    <img width="90" height="90"
                                                                        class="img-fluid rounded"
                                                                        src="images/checkout/03.jpg" alt="">
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <h6>Một Đời Quản Trị</h6>
                                                                    <p>x1</p>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <p>Tổng tiền (1 sản phẩm): <label> 100.000đ</label>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <p class="text-danger">Đã hủy đơn hàng
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <hr>
                                                    <label>5 ngày trước <i class="ri-arrow-right-s-line"></i></label>
                                                    <li><a href="profile.php">
                                                            <div class="media align-items-center">
                                                                <div class="col-sm-1">

                                                                    <img width="90" height="90"
                                                                        class="img-fluid rounded"
                                                                        src="images/browse-books/09.jpg" alt="">
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <h6>My First Love</h6>
                                                                    <p>x1</p>
                                                                    <h6>2 sản phẩm khác <i
                                                                            class="ri-arrow-down-s-line"></i></h6>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <p>Tổng tiền (3 sản phẩm): <label> 255.000đ</label>
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <p class="text-primary">Đã hoàn tất giao đơn hàng
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="emailandsms" role="tabpanel">
                                    <div class="iq-card">
                                        <div class="iq-card-header d-flex justify-content-between">
                                            <div class="iq-header-title">
                                                <h4 class="card-title">Email và SMS</h4>
                                            </div>
                                        </div>
                                        <div class="iq-card-body">
                                            <form>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-8 col-md-3" for="emailnotification">Thông báo tới
                                                        Email :</label>
                                                    <div class="col-4 col-md-9 custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="emailnotification" checked="">
                                                        <label class="custom-control-label"
                                                            for="emailnotification"></label>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-8 col-md-3" for="smsnotification">Thông báo tới
                                                        SMS:</label>
                                                    <div class="col-4 col-md-9 custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="smsnotification" checked="">
                                                        <label class="custom-control-label"
                                                            for="smsnotification"></label>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3" for="npass">Khi nào gửi Email</label>
                                                    <div class="col-md-9">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="email01">
                                                            <label class="custom-control-label" for="email01">Bạn có
                                                                thông báo mới.</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="email02">
                                                            <label class="custom-control-label" for="email02">Bạn đã gửi
                                                                một tin nhắn trực tiếp</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="email03" checked="">
                                                            <label class="custom-control-label" for="email03">Ai đó thêm
                                                                bạn làm kết nối</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row align-items-center">
                                                    <label class="col-md-3" for="npass">Khi nào cần báo email</label>
                                                    <div class="col-md-9">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="email04">
                                                            <label class="custom-control-label" for="email04"> Theo đơn
                                                                đặt hàng mới.</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="email05">
                                                            <label class="custom-control-label" for="email05"> Phê duyệt
                                                                thành viên mới</label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="email06" checked="">
                                                            <label class="custom-control-label" for="email06"> Đăng ký
                                                                thành viên</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary mr-2">Lưu</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="manage-contact" role="tabpanel">
                                    <div class="iq-card">
                                        <div class="iq-card-header d-flex justify-content-between">
                                            <div class="iq-header-title">
                                                <h4 class="card-title">Quản lý liên hệ</h4>
                                            </div>
                                        </div>
                                        <div class="iq-card-body">
                                            <form>
                                                <div class="form-group">
                                                    <label for="cno">Số liên lạc:</label>
                                                    <input type="text" class="form-control" id="cno" value="09******20">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="text" class="form-control" id="email"
                                                        value="email@gmail.com">
                                                </div>
                                                <button type="submit" class="btn btn-primary mr-2">Lưu</button>
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