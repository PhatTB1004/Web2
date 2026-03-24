<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

    <!-- Page Content -->
    <div id="content-page" class="content-page">
      <div class="container-fluid">

        <div class="row">
          <div class="col-sm-12">
            <div class="iq-card mb-4">
              <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                  <h4 class="card-title">Tỉ lệ lợi nhuận theo loại sản phẩm</h4>
                </div>
              </div>
              <div class="iq-card-body">
                <form>
                  <div class="row">
                    <ul class="iq-edit-profile d-flex nav nav-pills">
                      <li class="col-md-3 p-2">
                        <div class="d-flex align-items-center mb-1">
                          <label class="mb-0 font-weight-bold">General Books</label>
                          <a href="#General Books" class="text-primary" title="Chi tiết">
                            <i class="ri-arrow-down-s-line" style="font-size: 20px;"></i>
                          </a>
                        </div>
                        <input type="number" class="form-control" value="20">
                      </li>
                      <li class="col-md-3 p-2">
                        <div class="d-flex align-items-center mb-1">
                          <label class="mb-0 font-weight-bold">History Books</label>
                          <a href="#General Books" class="text-primary" title="Chi tiết">
                            <i class="ri-arrow-down-s-line" style="font-size: 20px;"></i>
                          </a>
                        </div>
                        <input type="number" class="form-control" value="20">
                      </li>
                      <li class="col-md-3 p-2">
                        <div class="d-flex align-items-center mb-1">
                          <label class="mb-0 font-weight-bold">Film & Photography</label>
                          <a href="#General Books" class="text-primary" title="Chi tiết">
                            <i class="ri-arrow-down-s-line" style="font-size: 20px;"></i>
                          </a>
                        </div>
                        <input type="number" class="form-control" value="20">
                      </li>
                      <li class="col-md-3 p-2">
                        <div class="d-flex align-items-center mb-1">
                          <label class="mb-0 font-weight-bold">Computers & Internet</label>
                          <a href="#General Books" class="text-primary" title="Chi tiết">
                            <i class="ri-arrow-down-s-line" style="font-size: 20px;"></i>
                          </a>
                        </div>
                        <input type="number" class="form-control" value="20">
                      </li>
                      <li class="col-md-3 p-2">
                        <div class="d-flex align-items-center mb-1">
                          <label class="mb-0 font-weight-bold">Sports</label>
                          <a href="#General Books" class="text-primary" title="Chi tiết">
                            <i class="ri-arrow-down-s-line" style="font-size: 20px;"></i>
                          </a>
                        </div>
                        <input type="number" class="form-control" value="20">
                      </li>
                      <li class="col-md-3 p-2">
                        <div class="d-flex align-items-center mb-1">
                          <label class="mb-0 font-weight-bold">Horror Story</label>
                          <a href="#General Books" class="text-primary" title="Chi tiết">
                            <i class="ri-arrow-down-s-line" style="font-size: 20px;"></i>
                          </a>
                        </div>
                        <input type="number" class="form-control" value="20">
                      </li>
                    </ul>
                  </div>
                  <button type="submit" class="btn btn-primary mt-2">Lưu thay đổi</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="iq-card">
              <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                  <h4 class="card-title">Giá vốn và giá bán theo sản phẩm</h4>
                </div>
              </div>
              
              <div class="tab-pane fade active show" id="General Books" role="tabpanel">
                <div class="iq-card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>STT</th>
                          <th>Hình ảnh</th>
                          <th>Tên sách</th>
                          <th>Loại</th>
                          <th>Giá vốn (₫)</th>
                          <th>% loại</th>
                          <th>% sản phẩm</th>
                          <th>Giá bán (₫)</th>
                          <th>Hoạt động</th>
                        </tr>
                      </thead>
                      <tbody>

                        <tr>
                          <td>1</td>
                          <td><img src="../images/browse-books/01.jpg" alt=""></td>
                          <td>Reading on the Worlds</td>
                          <td>General Books</td>
                          <td>89.000</td>
                          <td>20%</td>
                          <td><input type="number" class="form-control form-control-sm" value="20"></td>
                          <td class="price">106.800</td>
                          <td>

                            <a class="bg-danger text-white p-2 rounded" href="#" title="Lưu"><i
                                class="ri-save-line"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td><img src="../images/browse-books/02.jpg" alt=""></td>
                          <td>The Catcher in the Rye</td>
                          <td>General Books</td>
                          <td>89.000</td>
                          <td>20%</td>
                          <td><input type="number" class="form-control form-control-sm" value="25"></td>
                          <td class="price">111.250</td>
                          <td>

                            <a class="bg-danger text-white p-2 rounded" href="#" title="Lưu"><i
                                class="ri-save-line"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td><img src="../images/browse-books/03.jpg" alt=""></td>
                          <td>Little Black Book</td>
                          <td>General Books</td>
                          <td>129.000</td>
                          <td>20%</td>
                          <td><input type="number" class="form-control form-control-sm" value="20"></td>
                          <td class="price">154.800</td>
                          <td>

                            <a class="bg-danger text-white p-2 rounded" href="#" title="Lưu"><i
                                class="ri-save-line"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td><img src="../images/browse-books/05.jpg" alt=""></td>
                          <td>Absteact On Background</td>
                          <td>General Books</td>
                          <td>99.000</td>
                          <td>20%</td>
                          <td><input type="number" class="form-control form-control-sm" value="25"></td>
                          <td class="price">123.750</td>
                          <td>

                            <a class="bg-danger text-white p-2 rounded" href="#" title="Lưu"><i
                                class="ri-save-line"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td><img src="../images/browse-books/09.jpg" alt=""></td>
                          <td>Conversion Erik Routley</td>
                          <td>General Books</td>
                          <td>79.000</td>
                          <td>20%</td>
                          <td><input type="number" class="form-control form-control-sm" value="20"></td>
                          <td class="price">118.500</td>
                          <td>

                            <a class="bg-danger text-white p-2 rounded" href="#" title="Lưu"><i
                                class="ri-save-line"></i></a>
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
  </div>
  
<?php include "includes/footer.php"; ?>