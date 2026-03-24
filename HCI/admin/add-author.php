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
                           <h4 class="card-title">Thêm tác giả</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <form action="author.php">
                           <div class="form-group">
                              <label>Tên tác giả:</label>
                              <input type="text" class="form-control">
                           </div>
                           <div class="form-group">
                              <label>Hồ sơ tác giả:</label>
                              <div class="custom-file">
                                 <input type="file" class="custom-file-input" id="customFile">
                                 <label class="custom-file-label" for="customFile">Chọn tập tin</label>
                              </div>
                           </div>
                           <div class="form-group">
                              <label>Email tác giả:</label>
                              <input type="email" class="form-control">
                           </div>
                           <div class="form-group">
                              <label>Nội dung:</label>
                              <textarea class="form-control" rows="4"></textarea>
                           </div>
                           <button type="submit" class="btn btn-primary">Gửi</button>
                           <button type="reset" class="btn btn-danger">Trở lại</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

<?php include "includes/footer.php"; ?>