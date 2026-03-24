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
                           <h4 class="card-title">Sách</h4>
                        </div>
                        <div class="iq-card-header-toolbar d-flex align-items-center">
                           <a href="add-book.php" class="btn btn-primary">Thêm sách</a>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <div class="table-responsive">
                           <table class="data-tables table table-striped table-bordered" style="width:100%">
                              <thead>
                                 <tr>
                                    <th style="width: 3%;"> Mã </th>
                                    <th style="width: 12%;">Hình ảnh</th>
                                    <th style="width: 15%;">Tên sách</th>
                                    <th style="width: 13%;">Thể loại sách</th>
                                    <th style="width: 13%;">Tác giả sách</th>
                                    <th style="width: 18%;">Mô tả sách</th>
                                    <th style="width: 7%;">Giá</th>
                                    <th style="width: 7%;">Sách PDF</th>
                                    <th style="width: 15%;">Hoạt động</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td>S001</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/01.jpg" alt=""></td>
                                    <td>Reading on the Worlds</td>
                                    <td>General Books</td>
                                    <td>Jhone Steben</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>99.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="top" title="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="top" title="Xoá" href="#"><i class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S002</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/02.jpg" alt=""></td>
                                    <td>The Catcher in the Rye</td>
                                    <td>History Books</td>
                                    <td>Fritz Wold</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>99.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S003</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/03.jpg" alt=""></td>
                                    <td>Little Black Book</td>
                                    <td>Comic Books</td>
                                    <td>John Klok</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>199.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S004</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/04.jpg" alt=""></td>
                                    <td>Take On The Risk</td>
                                    <td>General Books</td>
                                    <td>George Strong</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>149.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S005</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/05.jpg" alt=""></td>
                                    <td>Absteact On Background</td>
                                    <td>Film & Photography</td>
                                    <td>Ichae Semos</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>299.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S006</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/06.jpg" alt=""></td>
                                    <td>Find The Wave Book</td>
                                    <td>General Books</td>
                                    <td>Fidel Martin</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>299.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S007</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/07.jpg" alt=""></td>
                                    <td>See the More Story</td>
                                    <td>Horror Story</td>
                                    <td>Jules Boutin</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>199.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S008</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/08.jpg" alt=""></td>
                                    <td>The Wikde Book</td>
                                    <td> Computers & Internet</td>
                                    <td>Kusti Franti</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>99.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S009</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/09.jpg" alt=""></td>
                                    <td>Conversion Erik Routley</td>
                                    <td>Sports</td>
                                    <td>Argele Intili</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>99.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
                                                class="ri-delete-bin-line"></i></a>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>S010</td>
                                    <td><img class="img-fluid rounded" src="../images/browse-books/10.jpg" alt=""></td>
                                    <td>The Leo Dominica</td>
                                    <td>General Books</td>
                                    <td>Henry Jurk</td>
                                    <td>
                                       <p class="mb-0">Cuốn sách đầu tiên của tôi, 'Reading the World' hay 'The World
                                          between Two Covers', được biết đến ở Hoa Kỳ, được lấy cảm hứng từ cuộc hành
                                          trình kéo dài một năm của tôi thông qua một cuốn sách từ mọi quốc gia trên thế
                                          giới mà tôi đã ghi lại </p>
                                    </td>
                                    <td>149.000đ</td>
                                    <td><a href="book-pdf.php"><i
                                             class="ri-file-fill text-secondary font-size-18"></i></a></td>
                                    <td>
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Sửa" href="fix-book.php"><i
                                                class="ri-pencil-line"></i></a>
                                          <a class="bg-primary" data-toggle="tooltip" data-placement="Delete" href="#"><i
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