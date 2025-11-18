<?php include_once './main.php';
if (!isset($_SESSION['username'])) {
   header('Location: /');
}
?>

<div class="card">
   <div class="card-body">
      <div class="mb-3">
         <div class="row text-center justify-content-center row-cols-3 row-cols-lg-6 g-1 g-lg-1">
            <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold false" href="/profile" style="background-color: rgb(255, 180, 115);">Tài khoản</a></div>
            <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold active" href="/lich-su" style="background-color: rgb(255, 180, 115);">Lịch sử GD</a></div>
         </div>
      </div>

      <table class="table">
         <tbody>
            <tr class="fw-semibold">
               <td>Tài khoản</td>
               <td><?php echo $row['username'] ?></td>
            </tr>
            <tr class="fw-semibold">
               <td>Avatar</td>
               <td>
                  <form id="avatarForm" enctype="multipart/form-data">
                     <input type="file" name="avatar" accept="image/png, image/jpeg, image/jpg, image/gif" required>
                     <button type="submit">Cập nhật avatar</button>
                  </form>
                  <div id="avatarMsg"></div>
               </td>
            </tr>
            <tr class="fw-semibold">
               <td>Mật khẩu</td>
               <td>*** (<a class="cursor-pointer text-primary" href="/change-password">Đổi mật khẩu</a>)</td>
            </tr>

            <tr class="fw-semibold">
               <td>Email</td>
               <td><?php echo $row['email'] ?>(<a class="cursor-pointer text-primary" href="/change-email">Đổi email</a>)</td>
            </tr>

            <tr class="fw-semibold">
               <td>Số dư</td>
               <td><?php echo number_format($row['coin']) ?> LCoin</td>
            </tr>

            <tr class="fw-semibold">
               <td>Số Lượng</td>
               <td><?php echo number_format($row['luong']) ?> LƯỢNG</td>
            </tr>

            <tr class="fw-semibold">
               <td>Số Điện Thoại</td>
               <td><?php echo maskEmail($row['phone']); ?> (<a class="cursor-pointer text-primary" href="/change-phone-number">Thay đổi</a>)</td>
            </tr>
            <?php
            function maskEmail($email)
            {
               $atPosition = strpos($email, '09');

               // Kiểm tra xem email có ít hơn 4 ký tự không
               if ($atPosition < 4) {
                  return str_repeat('*', strlen($email));
               }

               $maskedPart = str_repeat('*', $atPosition - 4); // Ẩn 4 ký tự đầu tiên
               return substr_replace($email, $maskedPart, 2, $atPosition - 4);
            }

            ?>


            <tr class="fw-semibold">
               <td>Trạng thái</td>
               <td class="text-success fw-bold">
                  <?php echo $row["activated"] == 1 ? "Đã kích hoạt" : "Chưa kích hoạt" ?>
               </td>
            </tr>

            <tr class="fw-semibold">
               <td>Ngày tham gia</td>
               <td><?php echo $row['created_at'] ?></td>
            </tr>
         </tbody>
      </table>
   </div>
</div>

<script>
   document.getElementById('avatarForm').addEventListener('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload_avatar.php', true);
      xhr.onload = function() {
         document.getElementById('avatarMsg').innerHTML = xhr.responseText;
         if (xhr.responseText.toLowerCase().includes('thành công')) {
            setTimeout(function() {
               location.reload();
            }, 1200); // Reload sau 1.2 giây
         }
      };
      xhr.send(formData);
   });
</script>

<?php include_once './end.php'; ?>