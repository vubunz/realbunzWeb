<?php
ob_start();

include './main.php';



function writeToLog($content)
{
  $logFile = './lichsu/addcoin/coin_balance_logs_' . date("j.n.Y") . '.log';
  $log = "Host: " . $_SERVER['REMOTE_ADDR'] . " - " . date("F j, Y, g:i a") . PHP_EOL .
    "Content: " . $content . PHP_EOL .
    "-------------------------" . PHP_EOL;
  file_put_contents($logFile, $log, FILE_APPEND);
}

if (!isset($_SESSION['username'])) {
  header('Location: /');
}

if (!checkAdmin($conn, $_SESSION['username'])) {
  header('Location: /');
}



ob_end_flush();
?>

<div class="card">
  <div class="card-body">
    <?php include('success.php'); ?>
    <?php include('error.php'); ?>
    <?php
    $user_id = null;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
      $search_username = $_POST["search_username"];

      $search_query = "SELECT id FROM users WHERE username = '$search_username'";
      $search_result = mysqli_query($conn, $search_query);

      if ($search_result && mysqli_num_rows($search_result) > 0) {
        $row = mysqli_fetch_assoc($search_result);
        $user_id = $row["id"];

        $info_query = "SELECT u.username, u.luong, u.name, u.isVip, u.coin FROM users u JOIN users p WHERE p.id = '$search_username'";
        $info_result = mysqli_query($conn, $info_query);

        if ($info_result) {
          $info_row = mysqli_fetch_assoc($info_result);

          if ($info_row) {
            echo "<table>";
            echo "<tr><td>Tên nhân vật:</td><td>" . $info_row["name"] . "name</td></tr>";
            echo "<tr><td>Số dư Lượng:</td><td>" . number_format($info_row["luong"]) . " luong</td></tr>";
            echo "<tr><td>Víp:</td><td>" . number_format($info_row["vip"]) . " vip</td></tr>";
            echo "<tr><td>Số dư Coin:</td><td>" . number_format($info_row["coin"]) . " Coin</td></tr>";
            echo "<tr><td>Tên:</td><td>" . number_format($info_row["XacThuc"]) . " Kích Hoạt</td></tr>";
            echo "</table>";

            $logContent = "{'username':'" . $_SESSION['username'] . "','action':'search_player','ip_address':'" . $_SERVER['REMOTE_ADDR'] . "','searched_player_id':'" . $user_id . "','searched_player_username':'" . $search_username . "'}";
            writeToLog($logContent);
            echo '
                      <form method="post" action="">
                          <label for="amount">Số tiền:</label>
                          <input type="number" name="amount" required>
                          <br>
                          <label for="currency">Loại tiền:</label>
                          <select name="currency">
                              <option value="luong">Lượng</option>
                              <option value="coin">Coin</option>
                              <option value="nap">nạp</option>
                              <option value="vip">vip</option>
                              <option value="XacThuc">Kích Hoạt</option>
                          </select>
                          <br>
                          <input type="hidden" name="user_id" value="' . $user_id . '">
                          <input type="submit" name="submit" value="Cộng Tiền">
                      </form>';
          } else {
            echo '<div style="text-align: center; color: red;">⚠️Điền Số Cần Nhập Ở Dưới</div>';
            echo '
                      <form method="post" action="">
                          <label for="amount">Số Tương Ứng Mục Chọn:</label>
                          <input type="number" name="amount" required>
                          <br>
                          <label for="currency">Loại tiền:</label>
                          <select name="currency">
                              <option value="luong">Lượng</option>
                              <option value="coin">Coin</option>
                              <option value="nap">nạp</option>
                              <option value="vip">vip</option>
                              <option value="XacThuc">Kích Hoạt</option>
                          </select>
                          <br>
                          <input type="hidden" name="user_id" value="' . $user_id . '">
                          <input type="submit" name="submit" value="Cộng Tiền">
                      </form>';
          } ?>
    <?php
        } else {
          echo "Không thể lấy thông tin người chơi! Lỗi: " . mysqli_error($conn);
        }
      } else {
        $_SESSION['error'] = "Không tìm thấy người chơi";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
      }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
      if (checkAdmin($conn, $_SESSION['username'])) {
        $user_id = $_POST["user_id"];

        $amount = $_POST["amount"];
        $currency = $_POST["currency"];

        $update_query = "";
        if ($currency == 'luong' || $currency == 'coin' || $currency == 'nap' || $currency == 'vip' || $currency == 'XacThuc') {
          $update_query = "UPDATE users SET $currency = $currency + $amount WHERE id = $user_id";
        } else {
          $update_query = "UPDATE name SET $currency = $currency + $amount WHERE id = $user_id";
        }

        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
          $_SESSION['success'] = "Đã Cộng tiền cho thằng " . $_SESSION['username'] . ".";
          header("Location: " . $_SERVER['REQUEST_URI']);
          exit(0);
          $logContent = "{'username':'" . $_SESSION['username'] . "','action':'add_money','ip_address':'" . $_SERVER['REMOTE_ADDR'] . "','added_user_id':'" . $user_id . "','amount':'" . $amount . "','currency':'" . $currency . "'}";
          writeToLog($logContent);
        } else {
          echo "Không thể cộng tiền! Lỗi: " . mysqli_error($conn);
        }
      } else {
        $_SESSION['error'] = "Bạn làm đéo có quyền :))";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
      }
    }

    mysqli_close($conn);
    ?>
    <form method="post" action="">
      <label for="search_username">Nhập tên người chơi:</label>
      <input type="text" name="search_username" required>
      <input type="submit" name="search" value="Tìm Kiếm">
    </form>
  </div>
</div>
<style>
  form {
    text-align: center;
    margin-bottom: 20px;
  }

  table {
    margin-top: 20px;
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  .input-group {
    margin-bottom: 10px;
  }

  .input-group label {
    display: block;
    margin-bottom: 5px;
  }

  .input-group input {
    margin-right: 5px;
    height: calc(1.5em + 15px);
  }

  .btn-group button {
    margin-right: 5px;
  }

  .alert {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
  }

  .alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
  }

  .alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
  }

  .alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
  }

  @media only screen and (max-width: 600px) {
    .search-container input[type="text"] {
      width: 100%;
      /* Thiết lập chiều rộng là 100% trên màn hình có chiều rộng tối đa 600px */
    }
  }

  .card1 {
    width: 100%;
    overflow: hidden;
  }

  .card-body1 {
    overflow-x: auto;
  }
</style>
<?php include_once './end.php'; ?>