<?php include "includes/header.php"; ?>

<?php include "includes/database.php"; ?>

        <!-- Sign in Start -->
        <section class="sign-in-page">
            <div class="container p-0">
                <div class="row no-gutters height-self-center">
                  <div class="col-sm-12 align-self-center page-content rounded">
                    <div class="row m-0">
                      <div class="col-sm-12 sign-in-page-data">
                          <div class="sign-in-from bg-primary rounded">
                              <h3 class="mb-0 text-center text-white">Đăng nhập</h3>
                              <p class="text-center text-white">Nhập email hoặc tên tài khoản và mật khẩu.</p>
                              <form class="mt-4 form-text">
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Email hoặc tên tài khoản:</label>
                                      <input type="email" class="form-control mb-0" id="exampleInputEmail1" placeholder="Nhập email hoặc tên tài khoản">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputPassword1">Mật khẩu</label>
                                      <input type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Nhập mật khẩu">
                                      <a href="#" class="float-right text-dark">Quên mật khẩu?</a>
                                  </div>
                                  <div class="d-inline-block w-100">
                                      <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                                          <input type="checkbox" class="custom-control-input" id="customCheck1">
                                          <label class="custom-control-label" for="customCheck1">Ghi nhớ</label>
                                      </div>
                                  </div>
                                  <div class="sign-info text-center">
                                      <a href="index.php" class="btn btn-white d-block w-100 mb-2">Đăng nhập</a>
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
        <!-- Sign in END -->
         
<?php include "includes/footer.php"; ?>
