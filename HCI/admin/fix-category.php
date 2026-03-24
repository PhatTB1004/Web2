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
                           <h4 class="card-title">Sửa loại sách</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <form action="category.php">
                           <div class="form-group">
                              <label>Tên loại sách:</label>
                              <input type="text" class="form-control" value="Sách chung">
                           </div>
                           <div class="form-group">
                              <label>Nội dung:</label>
                              <textarea class="form-control"
                                 rows="4">Sách là một khái niệm mở, hình thức sách còn được thay đổi và cấu thành các dạng khác nhau theo các phương thức chế tác và nhân bản khác nhau, tùy thuộc vào môi trường sống và sự phát triển...</textarea>
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