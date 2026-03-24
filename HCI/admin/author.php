<?php include "includes/header.php"; ?>

<?php include "includes/sidebar.php"; ?>

<?php include "../includes/database.php"; 
      $sql = "SELECT * FROM author";
      $result = mysqli_query($conn,$sql);
?>

      <!-- Page Content  -->
      <div id="content-page" class="content-page">
         <div class="container-fluid">
            <div class="row">
               <div class="col-sm-12">
                  <div class="iq-card">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Danh sách tác giả</h4>
                        </div>
                        <div class="iq-card-header-toolbar d-flex align-items-center">
                           <a href="add-author.php" class="btn btn-primary">Thêm tác giả</a>
                        </div>
                     </div>
                     <div class="iq-card-body">
                        <div class="table-responsive">
                           <table class="data-tables table table-striped table-bordered" style="width:100%">
                              <thead>
                                 <tr>
                                    <th style="width: 5%;">STT</th>
                                    <th style="width: 5%;">Hồ sơ</th>
                                    <th style="width: 20%;">Tên tác giả</th>
                                    <th style="width: 60%;">Mô tả tác giả</th>
                                    <th style="width: 10%;">Hoạt động</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $stt = 1; while($row = mysqli_fetch_assoc($result)){ ?>
                                 <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td> <img src="../images/author/<?php echo $row['image']; ?>" class="img-fluid avatar-50 rounded"> </td>
                                    <td> <?php echo $row['fullname']; ?> </td>
                                    <td> <p class="mb-0"> <?php echo $row['info']; ?> </p> </td>
                                    <td> 
                                       <div class="flex align-items-center list-user-action">
                                          <a class="bg-primary"
                                          href="edit-author.php?id=<?php echo $row['id']; ?>">
                                             <i class="ri-pencil-line"></i>
                                          </a>
                                          <a class="bg-primary"
                                          href="delete-author.php?id=<?php echo $row['id']; ?>">
                                             <i class="ri-delete-bin-line"></i>
                                          </a>
                                       </div>
                                    </td>
                                 </tr>
                                 <?php } ?>
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