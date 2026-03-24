<?php include "includes/header.php"; ?>

<?php include "includes/database.php"; ?>


  <section class="sign-in-page">
    <div class="container p-0">
      <div class="row no-gutters height-self-center">
        <div class="col-sm-12 align-self-center page-content rounded">
          <div class="row m-0">
            <div class="col-sm-12 sign-in-page-data">
              <div class="sign-in-from bg-primary rounded">
                <h3 class="mb-0 text-center text-white">Đăng ký</h3>
                <p class="text-center text-white">Nhập email hoặc tên tài khoản và mật khẩu.</p>
                <form class="mt-4 form-text">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Tên tài khoản</label>
                    <input type="text" class="form-control mb-0" id="exampleInputEmail1" placeholder="Nhập tên tài khoản">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2">Email</label>
                    <input type="email" class="form-control mb-0" id="exampleInputEmail2" placeholder="Nhập email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Mật khẩu</label>
                    <input type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Mật khẩu">
                  </div>
                  <div class="d-inline-block w-100">
                    <div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">
                      <input type="checkbox" class="custom-control-input" id="customCheck1">
                      <label class="custom-control-label" for="customCheck1">Tôi đồng ý
                        <a href="#" class="text-light">Điều khoản và Điều kiện</a></label>
                    </div>
                  </div>
                  <div class="sign-info text-center">
                    <a href="sign-in.php" class="btn btn-white d-block w-100 mb-2" onclick="saveData()">Đăng ký</a>
                    <span class="text-dark d-inline-block line-height-2">Đã có tài khoản?
                      <a href="sign-in.php" class="text-white">Đăng nhập</a></span>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php include "includes/footer.php"; ?>
