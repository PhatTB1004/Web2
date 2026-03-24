<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

         <!-- Page Content  -->
         <div id="content-page" class="content-page">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                           <h4 class="card-title mb-0">Thêm Phiếu Nhập Hàng</h4>
                        </div>

                        <div class="iq-card-body">
                           <form action="import.php">
                            <div class="form-group">
                                    <label>Mã phiếu</label>
                                    <p>PN011</p>
                                 </div>
                              <div class="form-group">
                                 <label>Ngày nhập:</label>
                                 <input type="date" class="form-control" required>
                              </div>
                              <div class="form-group">
                                    <label>Tên sách:</label>
                                    <input type="text" class="form-control" placeholder="Nhập tên sách" required>
                                 </div>
                              <div class="form-group">
                                 <label>Chọn sách:</label>
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

                              <div class="form-row">
                                 <div class="form-group col-md-6">
                                    <label>Giá nhập (₫):</label>
                                    <input type="number" class="form-control" placeholder="Nhập giá nhập" required>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label>Số lượng:</label>
                                    <input type="number" class="form-control" placeholder="Nhập số lượng" required>
                                 </div>
                              </div>

                              <div class="form-group">
                                 <label>Ghi chú:</label>
                                 <textarea class="form-control" rows="3" placeholder="Ghi chú thêm (nếu có)..."></textarea>
                              </div>

                              <button type="submit" class="btn btn-primary">Lưu phiếu nhập</button>
                              <button type="reset" class="btn btn-danger">Hủy</button>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

<?php include "includes/footer.php"; ?>
