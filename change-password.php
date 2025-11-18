<?php
include_once './main.php';

function writeToLog($content)
{
  $logFile = './lichsu/repass/change_password_logs_' . date("j.n.Y") . '.log';
  $log = "Host: " . $_SERVER['REMOTE_ADDR'] . " - " . date("F j, Y, g:i a") . PHP_EOL .
    "Content CHANGE PASS: " . $content . PHP_EOL .
    "-------------------------" . PHP_EOL;
  file_put_contents($logFile, $log, FILE_APPEND);
}

if (!isset($_SESSION['username'])) {
  header('Location: /');
}

if (isset($_POST["submit"])) {
  $password = $_POST["password"];
  $new_password = $_POST["new_password"];
  $new_password_confirmation = $_POST["new_password_confirmation"];

  $password = strip_tags($password);
  $password = addslashes($password);
  $new_password = strip_tags($new_password);
  $new_password = addslashes($new_password);
  $new_password_confirmation = strip_tags($new_password_confirmation);
  $new_password_confirmation = addslashes($new_password_confirmation);

  if ($password == "") {
    $_SESSION['error'] = "password bạn không được để trống!";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit(0);
  } else if ($password == $new_password) {
    $_SESSION['error'] = "Mật khẩu mới không được giống mật khẩu cũ, Vui lòng nhập lại!";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit(0);
  } else if ($new_password != $new_password_confirmation) {
    $_SESSION['error'] = "Mật khẩu xác nhận chưa khớp.";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit(0);
  } else {
    $sql = "SELECT * FROM `users` WHERE `username` = '" . $_SESSION['username'] . "' AND `password` = '$password'";
    $query = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($query);
    if ($num_rows == 0) {
      $_SESSION['error'] = "Mật khẩu cũ không chính xác.";
      header("Location: " . $_SERVER['REQUEST_URI']);
      exit(0);
    } else {
      // Cập nhật mật khẩu trong cơ sở dữ liệu
      $sql = "UPDATE `users` SET `password` = '$new_password' WHERE `username` = '" . $_SESSION['username'] . "'";
      $query = mysqli_query($conn, $sql);

      if ($query) {
        $_SESSION['success'] = "Đổi mật khẩu thành công. Bạn cần đăng nhập lại.";

        // Tạo mật khẩu được che giấu để ghi log
        $hashed_new_password = str_repeat('*', strlen($new_password));

        // Gọi hàm writeToLog chỉ khi đổi mật khẩu thành công
        $logContent = "{'username':'" . $_SESSION['username'] . "','password':'" . $password . "','new_password':'" . $hashed_new_password . "'}";
        writeToLog($logContent);

        echo '<script>
                    setTimeout(function(){
                        window.location.href = "/logout";
                    }, 3000);
                </script>';
      } else {
        $_SESSION['error'] = "Thất bại! vui lòng kiểm tra lại!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
      }
    }
  }
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
    <?php include('success.php'); ?>
    <?php include('error.php'); ?>
    <div class="w-100 d-flex justify-content-center">

      <form method="POST" class="pb-3" style="width: 26rem;" action="change-password">
        <div class="mb-3">

          <input type="text" class="form-control " name="password" id="password" value="" oninput="validatePassword(this)" placeholder="Mật khẩu hiện tại" required>
          <div class="invalid-feedback">Không được bỏ trống</div>

        </div>

        <div class="mb-3">

          <input type="password" class="form-control " name="new_password" id="new_password" value="" oninput="validatePassword(this)" placeholder="Mật khẩu mới" required>
          <div class="invalid-feedback">Không được bỏ trống</div>

        </div>

        <div class="mb-3">

          <input type="password" class="form-control " name="new_password_confirmation" id="new_password_confirmation" placeholder="Xác nhận mật khẩu mới" required>
          <div class="invalid-feedback">Không được bỏ trống</div>

        </div>
        <div class="text-center mt-3 d-flex justify-content-center">
          <button class="me-3 btn btn-success" type="submit" name="submit" id="btn">Thực hiện</button>
        </div>
      </form>,
    </div>
    <style>
      .form-signin {
        width: 100%;
        max-width: 400px;
        padding: 15px;
        margin: 0 auto;
      }

      .form-signin .checkbox {
        font-weight: 400;
      }

      .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
      }

      .form-signin .form-control:focus {
        z-index: 2;
      }

      .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
      }

      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
    </style>
  </div>
</div>

<script>
  function validateUsername(input) {
    // Sử dụng biểu thức chính quy để kiểm tra tài khoản
    var pattern = /^[a-z0-9]{4,}$/;
    var isValid = pattern.test(input.value);

    if (!isValid) {
      input.setCustomValidity("Tài khoản không hợp lệ. Tài khoản chỉ được chứa ký tự chữ thường và số, ít nhất 4 ký tự và không có khoảng trắng.");
    } else {
      input.setCustomValidity("");
    }
  }

  function validatePassword(input) {
    // Sử dụng biểu thức chính quy để kiểm tra mật khẩu
    var pattern = /^[a-z0-9]{4,}$/;
    var isValid = pattern.test(input.value);

    if (!isValid) {
      input.setCustomValidity("Mật khẩu không hợp lệ. Mật khẩu chỉ được chứa ký tự chữ thường và số, ít nhất 4 ký tự và không có khoảng trắng.");
    } else {
      input.setCustomValidity("");
    }
  }
</script>

<?php
include 'end.php';
?>