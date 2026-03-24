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
                           <h4 class="card-title">Thêm sách</h4>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <form action="books.php">
                           <div class="form-group">
                              <label>Tên sách:</label>
                              <input type="text" class="form-control">
                           </div>
                           <div class="form-group">
                              <label>Loại sách:</label>
                              <select class="form-control" id="exampleFormControlSelect1">
                                 <option selected="" disabled="">Loại sách</option>
                                 <option>General Books</option>
                                 <option>History Books</option>
                                 <option>Horror Story</option>
                                 <option>Arts Books</option>
                                 <option>Film & Photography</option>
                                 <option>Business & Economics</option>
                                 <option>Comics & Mangas</option>
                                 <option>Computers & Internet</option>
                                 <option> Sports</option>
                                 <option> Travel & Tourism</option>
                              </select>
                           </div>
                           <div class="form-group">
                              <label>Tác giả sách:</label>
                              <input type="text" class="form-control">
                           </div>
                           <div class="form-group">
                              <label>Hình ảnh:</label>
                              <div class="custom-file">
                                 <input type="file" class="custom-file-input" accept="image/png, image/jpeg">
                                 <label class="custom-file-label">Choose file</label>
                              </div>
                           </div>
                           <div class="form-group">
                              <label>Sách pdf:</label>
                              <div class="custom-file">
                                 <input type="file" class="custom-file-input"
                                    accept="application/pdf, application/vnd.ms-excel">
                                 <label class="custom-file-label">Chọn file</label>
                              </div>
                           </div>
                           <div class="form-group">
                              <label>Giá sách:</label>
                              <input type="text" class="form-control">
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