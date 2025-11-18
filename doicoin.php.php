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
<div id="doiluongThanhCong" class="modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <span class="close" onclick="closeDoiluongThanhcong()">&times;</span>
         <div class="modal-body text-center">
            <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 150px;"></a>
            <h2>Đổi lượng thành công</h2>
            <p id="doiluongThanhCongContent"></p>
            <button class="modal-close-btn" onclick="closeDoiluongThanhCong()">OK</button>
         </div>
      </div>
   </div>
</div>




<!-- Modal HTML -->
<div id="doiluongThatBai" class="modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <span class="close" onclick="closeDoiluongThatBai()">&times;</span>
         <div class="modal-body text-center">
            <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 150px;"></a>
            <h2>Đổi lượng thất bại</h2>
            <p id="doiluongThatBaiContent"></p>
            <button class="modal-close-btn" onclick="closeDoiluongThatBai()">OK</button>
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
      <div class="text-center fw-semibold fs-5">Đổi Coin ra Lượng
         <div class="text-center">
            <?php include('success.php'); ?>
            <?php include('error.php'); ?>
            <?php
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['username'])) {
               header('Location: /');
            }

            // Truy vấn thông tin người dùng sử dụng Prepared Statement
            $sql = "SELECT * FROM `users` WHERE `username` = ?";
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
            }

            // Mảng ánh xạ giữa giá trị Ecoin và Lượng tương ứng
            $ecoinToLượng = array(
               10000 => 30000,
               20000 => 60000,
               50000 => 150000,
               100000 => 300000,
               200000 => 600000,
               500000 => 1500000,
               // 1000000 => 3000000,
               // 2000000 => 6000000,
               // 5000000 => 15000000,
               // 10000000 => 30000000,
            );

            // Xử lý đổi Ecoin sang Lượng
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               $selectedAmount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;

               // Kiểm tra xem giá trị Ecoin có tồn tại trong mảng ánh xạ không
               if (array_key_exists($selectedAmount, $ecoinToLượng)) {
                  // Kiểm tra xem người dùng có đủ Ecoin không
                  if ($coin >= $selectedAmount) {
                     $luongToAdd = $ecoinToLượng[$selectedAmount];
                     $coinToSubtract = $selectedAmount;

                     // Cập nhật cột 'luong' và 'coin' trong cơ sở dữ liệu sử dụng Prepared Statement
                     $updateSql = "UPDATE `users` SET `luong` = `luong` + ?, `coin` = `coin` - ? WHERE `username` = ?";
                     $updateStmt = mysqli_prepare($conn, $updateSql);
                     mysqli_stmt_bind_param($updateStmt, "iis", $luongToAdd, $coinToSubtract, $_SESSION['username']);
                     $updateQuery = mysqli_stmt_execute($updateStmt);

                     if ($updateQuery) {
                        $_SESSION['success'] = "Bạn nhận được " . number_format($luongToAdd) . " Lượng";
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
            <div class="row text-center justify-content-center row-cols-2 row-cols-lg-3 g-2 g-lg-2 my-1 mb-2">
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer " onclick="handleClick(10000)">
                        <div id="button-10000" class="recharge-method-item false recharge-method-item" style="height: 90px;" data-value="10000">
                           <div class="text-primary">10,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">30,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(20000)">
                        <div id="button-20000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="20000">
                           <div class="text-primary">20,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">60,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(50000)">
                        <div id="button-50000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="50000">
                           <div class="text-primary">50,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">150,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(100000)">
                        <div id="button-100000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="100000">
                           <div class="text-primary">100,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">300,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(200000)">
                        <div id="button-200000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="200000">
                           <div class="text-primary">200,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">600,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(500000)">
                        <div id="button-500000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="500000">
                           <div class="text-primary">500,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">1,500,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(1000000)">
                        <div id="button-1000000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="1000000">
                           <div class="text-primary">1,000,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">3,000,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(2000000)">
                        <div id="button-2000000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="2000000">
                           <div class="text-primary">2,000,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">6,000,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div>
                  <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(5000000)">
                        <div id="button-5000000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="5000000">
                           <div class="text-primary">5,000,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">15,000,000 lượng</div>
                        </div>
                     </div>
                  </div>
               </div> -->
               <div>
                  <!-- <div class="col">
                     <div class="w-100 fw-semibold cursor-pointer" onclick="handleClick(5000000)">
                        <div id="button-5000000" class="recharge-method-item false false recharge-method-item" style="height: 90px;" data-value="5000000">
                           <div class="text-primary">10,000,000L</div>
                           <div class="center-text text-dark"><span>Nhận</span></div>
                           <div class="text-danger">30,000,000 lượng</div>
                        </div>
                     </div>
                  </div> -->
                  <!--    <input type="hidden" name="selected_amount" id="selected-amount-input" value="">-->

                  <input type="hidden" name="amount" id="selected-amount" required="">
               </div>
            </div>

            <div class="text-center">
               <p>
                  <?php
                  // Kiểm tra kết nối
                  if ($conn->connect_error) {
                     die("Kết nối đến cơ sở dữ liệu thất bại: " . $conn->connect_error);
                  }

                  // Lấy giá trị của 'luong' dựa trên 'username' từ biến session
                  $sessionUsername = $_SESSION['username'];
                  $sql = "SELECT luong FROM users WHERE username = '$sessionUsername'";
                  $result = $conn->query($sql);

                  // Kiểm tra có dữ liệu trả về không
                  if ($result->num_rows > 0) {
                     // Lấy giá trị của 'luong' từ dòng dữ liệu
                     $row = $result->fetch_assoc();

                     // Hiển thị giá trị luong
                     echo "<p>Có " . number_format($row['luong']) . " Lượng</p>";
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