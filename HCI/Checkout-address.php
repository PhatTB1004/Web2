<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

<?php include "includes/topnav.php"; ?>

<?php include "includes/database.php"; ?>

        <!-- Page Content  -->
        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Địa chỉ của bạn</h4>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="form-group">
                                    <label>Tên:</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Số điện thoại</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Địa chỉ:</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Chi tiết địa chỉ:</label>
                                    <input type="text" class="form-control"
                                        placeholder="Nhập các chi tiết khác (Không bắt buộc)">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Đặt làm mặc định</label>
                                    </div>
                                </div>

                                <a href="Checkout.php">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                    <button type="reset" class="btn btn-danger">Trở lại</button></a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper END -->

<?php include "includes/footer.php"; ?>