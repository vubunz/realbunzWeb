<?php
ob_start();
include './main.php';


// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
   // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
   header('Location: /');
   exit(); // Đảm bảo dừng việc thực thi mã PHP sau khi chuyển hướng
}
ob_end_flush();
?>




<script>
   document.addEventListener('DOMContentLoaded', function() {
      // Kiểm tra trạng thái đăng nhập
      <?php if (!isset($_SESSION['username'])) : ?>
         // Nếu chưa đăng nhập, mở modal đăng nhập
         var modalLogin = new bootstrap.Modal(document.getElementById('modalLogin'));
         modalLogin.show();
      <?php endif; ?>
   });
</script>

<!-- Modal HTML -->
<div id="doixuThanhCong" class="modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <span class="close" onclick="closeDoixuThanhcong()">&times;</span>
         <div class="modal-body text-center">
            <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 150px;"></a>
            <h2>Đổi xu thành công</h2>
            <p id="doixuThanhCongContent"></p>
            <button class="modal-close-btn" onclick="closeDoixuThanhCong()">OK</button>
         </div>
      </div>
   </div>
</div>




<!-- Modal HTML -->
<div id="doixuThatBai" class="modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <span class="close" onclick="closeDoixuThatBai()">&times;</span>
         <div class="modal-body text-center">
            <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 150px;"></a>
            <h2>Đổi xu thất bại</h2>
            <p id="doixuThatBaiContent"></p>
            <button class="modal-close-btn" onclick="closeDoixuThatBai()">OK</button>
         </div>
      </div>
   </div>
</div>


<style>
   /* CSS cho modal */
   .modal {
      /* ... */
      transition: opacity 0.3s ease-in-out;
      /* Thêm transition cho modal */
   }

   .modal.show {
      opacity: 1;
      /* Hiển thị modal mượt mà */
   }

   /* CSS cho nút OK */
   .modal-close-btn {
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
      transition: background-color 0.3s ease-in-out;
      /* Thêm transition cho nút OK */
   }

   /* Khi hover vào nút đóng */
   .modal-close-btn:hover {
      background-color: #0056b3;
   }
</style>






<div class="card">
   <div class="card-body">
      <div class="text-center fw-semibold fs-5">Đổi Coin ra xu
         <div class="text-center">
            <?php include('success.php'); ?>
            <?php include('error.php'); ?>
            <?php
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['username'])) {
               header('Location: /');
            }

            // Truy vấn thông tin người dùng sử dụng Prepared Statement
            $sql = "SELECT users.*, players.xu 
                    FROM users 
                    JOIN players ON users.id = players.user_id 
                    WHERE users.username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Kiểm tra số dòng kết quả
            $num_rows = mysqli_num_rows($result);

            while ($row = mysqli_fetch_array($result)) {
               // Truy cập và in ra dữ liệu 'luong'
               $luong = $row['luong'];
               $coin = $row['coin'];
               $xu = $row['xu'];
            }

            // Mảng ánh xạ giữa giá trị Ecoin và Lượng tương ứng
            $ecoinToXu = array(
               1000 => 10000000,
               10000 => 100000000,
               20000 => 210000000,
               50000 => 550000000,
            );

            // Xử lý đổi Ecoin sang Lượng
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               // Lấy lại thông tin user mới nhất
               $sql = "SELECT users.*, players.xu 
                       FROM users 
                       JOIN players ON users.id = players.user_id 
                       WHERE users.username = ?";
               $stmt = mysqli_prepare($conn, $sql);
               mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
               mysqli_stmt_execute($stmt);
               $result = mysqli_stmt_get_result($stmt);

               $row = mysqli_fetch_assoc($result);
               if ($row) {
                  $coin = $row['coin'];
                  $xu = $row['xu'];
                  $user_id = $row['id'];
               } else {
                  $_SESSION['error'] = "Không tìm thấy thông tin tài khoản! Hãy tạo nhân vật hoặc báo lỗi với Admin";
                  header("Location: " . $_SERVER['REQUEST_URI']);
                  exit(0);
               }

               $selectedAmount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

               if (array_key_exists($selectedAmount, $ecoinToXu)) {
                  if ($coin >= $selectedAmount) {
                     $xuToAdd = $ecoinToXu[$selectedAmount];
                     $coinToSubtract = $selectedAmount;

                     if (($xu + $xuToAdd) >= 2000000000) {
                        $_SESSION['error'] = "Số xu sau khi đổi sẽ vượt quá 2 tỷ! Vui lòng cất bớt xu vào rương trước khi đổi.";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                     }

                     // 1. Trừ coin ở bảng users
                     $updateUserSql = "UPDATE `users` SET `coin` = `coin` - ? WHERE `username` = ?";
                     $updateUserStmt = mysqli_prepare($conn, $updateUserSql);
                     mysqli_stmt_bind_param($updateUserStmt, "is", $coinToSubtract, $_SESSION['username']);
                     $updateUserQuery = mysqli_stmt_execute($updateUserStmt);

                     // 2. Cộng xu vào bảng players
                     // Lấy user_id từ $row['id'] (bạn đã lấy ở truy vấn trước)
                     $updatePlayerSql = "UPDATE `players` SET `xu` = `xu` + ? WHERE `user_id` = ?";
                     $updatePlayerStmt = mysqli_prepare($conn, $updatePlayerSql);
                     mysqli_stmt_bind_param($updatePlayerStmt, "ii", $xuToAdd, $user_id);
                     $updatePlayerQuery = mysqli_stmt_execute($updatePlayerStmt);

                     if ($updateUserQuery && $updatePlayerQuery) {
                        $_SESSION['success'] = "Bạn đã đổi $coinToSubtract Ecoin lấy $xuToAdd Xu thành công!";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                     } else {
                        $_SESSION['error'] = "Đã xảy ra lỗi khi đổi, vui lòng liên hệ Admin để nhận đền bù.";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                     }
                  } else {
                     $_SESSION['error'] = "Số dư không đủ !<a href='/recharge' style='color: blue;'> Bơm lúa ngay</a>";
                     header("Location: " . $_SERVER['REQUEST_URI']);
                     exit(0);
                  }
               } else {
                  $_SESSION['error'] = "Vui lòng chọn mốc đổi.";
                  header("Location: " . $_SERVER['REQUEST_URI']);
                  exit(0);
               }
            }

            // Sau khi xử lý POST, lấy lại thông tin user để hiển thị số dư mới nhất
            $sql = "SELECT users.*, players.xu 
                    FROM users 
                    JOIN players ON users.id = players.user_id 
                    WHERE users.username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);
            if ($row) {
               $coin = $row['coin'];
               $xu = $row['xu'];
               $user_id = $row['id'];
            }

            ?>


         </div>

         <style>
            /* CSS cho modal */
            .modal {
               /* ... */
               transition: opacity 0.3s ease-in-out;
               /* Thêm transition cho modal */
            }

            .modal.show {
               opacity: 1;
               /* Hiển thị modal mượt mà */
            }

            /* CSS cho nút OK */
            .modal-close-btn {
               background-color: #007BFF;
               color: white;
               border: none;
               padding: 10px 20px;
               border-radius: 5px;
               cursor: pointer;
               font-size: 16px;
               margin-top: 10px;
               transition: background-color 0.3s ease-in-out;
               /* Thêm transition cho nút OK */
            }

            /* Khi hover vào nút đóng */
            .modal-close-btn:hover {
               background-color: #0056b3;
            }
         </style>


         <form method="POST">
            <span class="text-danger"></span>
      </div>
      <div class="d-flex justify-content-center">
         <div class="col-md-8">
            <div class="row text-center justify-content-center row-cols-2 row-cols-lg-4 g-2 g-lg-2 my-1 mb-2">
               <div class="col">
                  <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(1000)">
                     <div id="button-1000" class="recharge-method-item" style="height: 90px;" data-value="1000">
                        <div class="text-primary">1,000</div>
                        <div class="center-text text-dark"><span>Nhận</span></div>
                        <div class="text-danger">10,000,000 Xu</div>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(10000)">
                     <div id="button-10000" class="recharge-method-item" style="height: 90px;" data-value="10000">
                        <div class="text-primary">10,000</div>
                        <div class="center-text text-dark"><span>Nhận</span></div>
                        <div class="text-danger">100,000,000 Xu</div>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(20000)">
                     <div id="button-20000" class="recharge-method-item" style="height: 90px;" data-value="20000">
                        <div class="text-primary">20,000</div>
                        <div class="center-text text-dark"><span>Nhận</span></div>
                        <div class="text-danger">210,000,000 Xu</div>
                     </div>
                  </div>
               </div>
               <div class="col">
                  <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(50000)">
                     <div id="button-50000" class="recharge-method-item" style="height: 90px;" data-value="50000">
                        <div class="text-primary">50,000</div>
                        <div class="center-text text-dark"><span>Nhận</span></div>
                        <div class="text-danger">550,000,000 Xu</div>
                     </div>
                  </div>
               </div>
               <input type="hidden" name="amount" id="selected-amount" required="">
            </div>
         </div>
      </div>
      <div class="text-center">
         <p>
            <?php
            // Kiểm tra kết nối
            if ($conn->connect_error) {
               die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
            }

            // Lấy giá trị của 'xu' dựa trên 'username' từ biến session
            $sessionUsername = $_SESSION['username'];
            $sql = "SELECT players.xu 
                       FROM users 
                       JOIN players ON users.id = players.user_id 
                       WHERE users.username = '$sessionUsername'";
            $result = $conn->query($sql);

            // Kiểm tra có dữ liệu trả về không
            if ($result->num_rows > 0) {
               // Lấy giá trị của 'xu' từ dòng dữ liệu
               $row = $result->fetch_assoc();

               // Hiển thị giá trị xu
               echo "<p>Có " . number_format($row['xu']) . " Xu</p>";
            } else {
               echo "Không có dữ liệu của người chơi";
            }

            ?></p>
      </div>



      <!-- <div class="text-center">
         <div class="fw-semibold fs-6">Chọn nhân vật</div>
      </div>
      <div class="text-danger text-center fw-semibold mt-3 mb-2">Tài khoản chưa có nhân vật nào</div> -->
      <div class="text-center mt-4">
         <button id="confirm" type="button" onclick="onClickExchange()" class="w-50 rounded-3 btn btn-primary btn-sm">Xác nhận</button>

      </div>
      <div class="text-center"><small class="fw-semibold"><a href="/lich-su">Lịch sử giao dịch</a></small></div>
      <!-- </div>
   </div>
</div>-->
      <div class="modal fade" id="modalConfirmExchange" tabindex="-1" aria-labelledby="modalConfirmExchangeLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <div class="my-2">
                     <div class="text-center"><a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 250px;"></a></div>
                  </div>
                  <div class="text-center fw-semibold">
                     <div id="noti" style="text-align: center;"></div>
                     <div class="text-white text-center mb-2" id="waiting-times"></div>
                     <div class="fs-6 mb-2">Bạn thoát game trước khi thực hiện giao dịch chưa?</div>
                     <div id="noti-active"></div>
                     <span>Bạn phải thoát game trước khi giao dịch rồi vào lại game để tránh phát sinh lỗi trong quá trình cộng tiền</span>
                     <div class="mt-2 aci"><button type="submit" id="confirmExchange" onclick="handleConfirm()" class="btn-rounded btn btn-primary btn-sm">Xác nhận đã thoát</button></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
<style>
   .centered-container {
      display: flex;
      justify-content: center;
      align-items: center;

   }

   .chonnhanvat {
      background-color: #e5fdff;
      border: 2px solid #0096ff;
      transition: border-color 0.3s, background-color 0.05s;
   }

   .chonnhanvat:hover {
      border-color: #146c43;
   }

   .chonnhanvat.selected {
      border-color: #136841;
      background-color: #faeda7;
   }

   .chonnhanvat {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      border-radius: 15px;
      overflow: hidden;
   }

   .chonnhanvat img {
      max-width: 50px;
      /* Điều chỉnh kích thước ảnh theo ý muốn */
      max-height: 50px;
      /* Điều chỉnh kích thước ảnh theo ý muốn */
   }
</style>

<script>
   // Sử dụng JavaScript để xử lý việc chọn tùy chọn và cập nhật giá trị trong input ẩn
   const rechargeMethodItems = document.querySelectorAll(".recharge-method-item");
   const selectedAmountInput = document.getElementById("selected-amount");

   rechargeMethodItems.forEach((item) => {
      item.addEventListener("click", () => {
         rechargeMethodItems.forEach((element) => {
            element.classList.remove("selected");
         });

         item.classList.add("selected");
         selectedAmountInput.value = item.getAttribute("data-value");
      });
   });


   function onClickExchange() {
      $("#modalConfirmExchange").modal("show");
   }
   let selected;
   let beforeSelected;

   function handleClick(index) {
      selected = index;
      $(`#button-` + selected + ``).css('background-color', '#faeda7');
      if (beforeSelected) {
         $(`#button-` + beforeSelected + ``).css('background-color', '');
      }
      beforeSelected = index;
   }

   //nude nhân vật
   document.addEventListener("DOMContentLoaded", function() {
      const rechargeItems = document.querySelectorAll(".chonnhanvat");

      // Lặp qua tất cả các ô lựa chọn và thêm sự kiện click cho mỗi ô
      rechargeItems.forEach(function(item) {
         item.addEventListener("click", function() {
            // Loại bỏ lớp `.selected` từ tất cả các ô lựa chọn
            rechargeItems.forEach(function(item) {
               item.classList.remove("selected");
            });

            // Thêm lớp `.selected` cho ô lựa chọn được chọn
            item.classList.add("selected");
         });
      });
   });
</script>
</form>
<?php include 'end.php' ?>
</body>

</html>