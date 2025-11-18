<?php
ob_start();
include_once './main.php';
  if (!isset($_SESSION['username'])) {
	header('Location: /');
  }


  if (!checkAdmin($conn, $_SESSION['username'])) {
    header('Location: /');
    exit();
}
ob_end_flush();
?>
<?php

// Xử lý cập nhật khi form được gửi đi
if (isset($_POST['id'])) {
    $user_id = $_POST['id'];

    // Lấy dữ liệu hiện tại từ cơ sở dữ liệu
    $sql_old_data = "SELECT * FROM ninja WHERE id = '$user_id'";
    $result_old_data = $conn->query($sql_old_data);
    $row_old_data = $result_old_data->fetch_assoc();

    // Lặp qua tất cả các trường
    foreach ($_POST as $key => $value) {
        // Bỏ qua user_id vì không muốn cập nhật nó
        if ($key === 'id') {
            continue;
        }

        // Kiểm tra xem giá trị mới có khác với giá trị cũ không
        $old_value = $row_old_data[$key];

        if ($old_value !== $value) {
            // Thực hiện cập nhật nếu giá trị khác nhau
            $sql_update = "UPDATE ninja SET $key = '$value' WHERE id = '$user_id'";
            if ($conn->query($sql_update) !== TRUE) {
                echo "Lỗi cập nhật: " . $conn->error;
            }
        }
    }

    echo "Cập nhật thành công!";
}

$conn->close();
?>
