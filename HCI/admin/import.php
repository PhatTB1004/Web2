<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

        <!-- Page Content  -->

        <div id="content-page" class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="iq-card">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Nhập sách</h4>
                                </div>
                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <form class="form-inline my-2 my-lg-0">
                                        <input class="form-control mr-sm-2" type="search"
                                            placeholder="Tìm phiếu nhập..." aria-label="Search">
                                        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Tìm</button>
                                    </form>
                                    <a href="add-import.php" class="btn btn-primary ml-2">Thêm phiếu nhập</a>
                                </div>
                            </div>
                            <div class="iq-card-body">
                                <div class="table-responsive">
                                    <table class="data-tables table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">STT</th>
                                                <th width="10%">Mã phiếu</th>
                                                <th width="15%">Ngày nhập</th>
                                                <th width="15%">Số lượng</th>
                                                <th width="20%">Tổng tiền</th>
                                                <th width="15%">Trạng thái</th>
                                                <th width="20%">Hoạt động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>PN001</td>
                                                <td>2025-11-03</td>
                                                <td>5</td>
                                                <td>2.500.000 đ</td>
                                                <td><span class="badge badge-warning">Chưa hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-primary" title="Sửa"
                                                            href="fix-import.php"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-primary" title="Hoàn thành" href="#"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-primary" title="Xoá" href="#"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>PN002</td>
                                                <td>2025-11-02</td>
                                                <td>3</td>
                                                <td>1.800.000 đ</td>
                                                <td><span class="badge badge-success">Đã hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>PN003</td>
                                                <td>2025-11-01</td>
                                                <td>4</td>
                                                <td>2.200.000 đ</td>
                                                <td><span class="badge badge-warning">Chưa hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-primary" title="Sửa"
                                                            href="fix-import.php"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-primary" title="Hoàn thành" href="#"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-primary" title="Xoá" href="#"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>PN004</td>
                                                <td>2025-10-29</td>
                                                <td>6</td>
                                                <td>3.100.000 đ</td>
                                                <td><span class="badge badge-success">Đã hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>PN005</td>
                                                <td>2025-10-28</td>
                                                <td>2</td>
                                                <td>900.000 đ</td>
                                                <td><span class="badge badge-warning">Chưa hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-primary" href="fix-import.php"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-primary" href="#"><i class="ri-check-line"></i></a>
                                                        <a class="bg-primary" href="#"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>PN006</td>
                                                <td>2025-10-25</td>
                                                <td>7</td>
                                                <td>3.800.000 đ</td>
                                                <td><span class="badge badge-success">Đã hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>PN007</td>
                                                <td>2025-10-20</td>
                                                <td>8</td>
                                                <td>4.500.000 đ</td>
                                                <td><span class="badge badge-warning">Chưa hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-primary" href="fix-import.php"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-primary" href="#"><i class="ri-check-line"></i></a>
                                                        <a class="bg-primary" href="#"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>PN008</td>
                                                <td>2025-10-15</td>
                                                <td>10</td>
                                                <td>5.200.000 đ</td>
                                                <td><span class="badge badge-success">Đã hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>PN009</td>
                                                <td>2025-10-10</td>
                                                <td>4</td>
                                                <td>1.700.000 đ</td>
                                                <td><span class="badge badge-warning">Chưa hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-primary" href="fix-import.php"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-primary" href="#"><i class="ri-check-line"></i></a>
                                                        <a class="bg-primary" href="#"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>PN010</td>
                                                <td>2025-10-05</td>
                                                <td>9</td>
                                                <td>4.800.000 đ</td>
                                                <td><span class="badge badge-success">Đã hoàn thành</span></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-pencil-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-check-line"></i></a>
                                                        <a class="bg-secondary disabled"><i
                                                                class="ri-delete-bin-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "includes/footer.php"; ?>