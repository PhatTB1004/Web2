<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

    <!-- Page Content -->
    <div id="content-page" class="content-page">
      <div class="container-fluid">

        <!-- Bộ lọc -->
        <div class="iq-card mb-4">
          <div class="iq-card-header d-flex justify-content-between align-items-center">
            <div class="iq-header-title">
              <h4 class="card-title mb-0">Tra cứu số lượng tồn kho</h4>
            </div>
          </div>
          <div class="iq-card-body">
            <form class="row">
              <div class="form-group col-md-3">
                <label>Chọn loại sách:</label>
                <select class="form-control">
                  <option>Tất cả</option>
                  <option>General Books</option>
                  <option>History Books</option>
                  <option>Comic Books</option>
                  <option>Sports</option>
                  <option>Computers & Internet</option>
                  <option>Film & Photography</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label>Tên sách:</label>
                <input type="text" class="form-control" placeholder="Nhập tên sách...">
              </div>
              <div class="form-group col-md-3">
                <label>Ngày tra cứu:</label>
                <input type="date" class="form-control" value="2025-05-10">
              </div>
              <div class="form-group col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Tra cứu</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Bảng tồn kho -->
        <div class="iq-card">
          <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
              <h4 class="card-title">Danh sách sản phẩm tồn kho</h4>
            </div>
          </div>
          <div class="iq-card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>STT</th>
                    <th>Tên sách</th>
                    <th>Loại</th>
                    <th>Ngày cập nhật</th>
                    <th>Nhập</th>
                    <th>Xuất</th>
                    <th>Tồn</th>
                    <th>Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Reading on the Worlds</td>
                    <td>General Books</td>
                    <td>2025-11-05</td>
                    <td>50</td>
                    <td>47</td>
                    <td>3</td>
                    <td><span class="badge badge-danger">Sắp hết hàng</span></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>The Catcher in the Rye</td>
                    <td>History Books</td>
                    <td>2025-11-05</td>
                    <td>100</td>
                    <td>65</td>
                    <td>35</td>
                    <td><span class="badge badge-success">Còn hàng</span></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Little Black Book</td>
                    <td>Comic Books</td>
                    <td>2025-11-05</td>
                    <td>60</td>
                    <td>55</td>
                    <td>5</td>
                    <td><span class="badge badge-danger">Sắp hết hàng</span></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>Take On The Risk</td>
                    <td>General Books</td>
                    <td>2025-11-05</td>
                    <td>80</td>
                    <td>40</td>
                    <td>40</td>
                    <td><span class="badge badge-success">Còn hàng</span></td>
                  </tr>
                  <tr>
                    <td>5</td>
                    <td>Absteact On Background</td>
                    <td>Film & Photography</td>
                    <td>2025-11-05</td>
                    <td>90</td>
                    <td>88</td>
                    <td>2</td>
                    <td><span class="badge badge-danger">Sắp hết hàng</span></td>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td>Find The Wave Book</td>
                    <td>General Books</td>
                    <td>2025-11-05</td>
                    <td>120</td>
                    <td>110</td>
                    <td>10</td>
                    <td><span class="badge badge-warning">Số lượng thấp</span></td>
                  </tr>
                  <tr>
                    <td>7</td>
                    <td>See the More Story</td>
                    <td>Horror Story</td>
                    <td>2025-11-05</td>
                    <td>70</td>
                    <td>30</td>
                    <td>40</td>
                    <td><span class="badge badge-success">Còn hàng</span></td>
                  </tr>
                  <tr>
                    <td>8</td>
                    <td>The Wikde Book</td>
                    <td>Computers & Internet</td>
                    <td>2025-11-05</td>
                    <td>60</td>
                    <td>59</td>
                    <td>1</td>
                    <td><span class="badge badge-danger">Sắp hết hàng</span></td>
                  </tr>
                  <tr>
                    <td>9</td>
                    <td>Conversion Erik Routley</td>
                    <td>Sports</td>
                    <td>2025-11-05</td>
                    <td>120</td>
                    <td>80</td>
                    <td>40</td>
                    <td><span class="badge badge-success">Còn hàng</span></td>
                  </tr>
                  <tr>
                    <td>10</td>
                    <td>The Leo Dominica</td>
                    <td>General Books</td>
                    <td>2025-11-05</td>
                    <td>99</td>
                    <td>90</td>
                    <td>9</td>
                    <td><span class="badge badge-warning">Số lượng thấp</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

<?php include "includes/footer.php"; ?>