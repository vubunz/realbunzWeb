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
<div class="card">
    <div class="card-body">
<?php include('success.php'); ?>
<?php include('error.php'); ?>
<?php
// Kiểm tra kết nối cơ sở dữ liệu
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Xử lý form sửa đổi nội dung
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (checkAdmin($conn, $_SESSION['username'])) {
    if (isset($_POST["edit"])) {
        $alertId = $_POST["alertId"];
        $newContent = $_POST["newContent"];

        $sqlUpdate = "UPDATE alert SET content = '$newContent' WHERE id = $alertId";

        if ($conn->query($sqlUpdate) === TRUE) {
            // Lấy địa chỉ IP của người dùng
            $ipAddress = $_SERVER['REMOTE_ADDR'];

            // Hiển thị thông báo bằng mã JavaScript
            echo "<script>
                if ('$ipAddress') {
                    var notification = document.createElement('div');
                    notification.innerHTML = 'Sửa thông báo thành công';
                    notification.classList.add('notification');
                    document.body.appendChild(notification);

                    setTimeout(function () {
                        notification.style.opacity = '0';
                        setTimeout(function () {
                            document.body.removeChild(notification);
                        }, 1000);
                    }, 2345);
                }
            </script>";
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
} else {
    $_SESSION['error'] = "Không đủ thẩm quyền!";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit(0);
}
}

?>

<style>
    /*.notification {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: black;
        color: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 9999;
    }*/
    .notification {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 15px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border-radius: 5px;
        opacity: 1;
        transition: opacity 1s ease-in-out;
    }
        /* CSS để căn giữa phần sửa thông báo */
        .edit-container {
        width: 50%;
        margin: auto;
        text-align: center;
    }

    .center-textarea {
        width: 100%;
        box-sizing: border-box;
        text-align: center;
        resize: both; /* Cho phép kéo rộng và cao của textarea */
        overflow: auto; /* Hiển thị thanh trượt nếu nội dung quá lớn */
    }
</style>

<center>
    <h2 style="color: black;">Sửa thông báo</h2>
</center>

<?php
// Truy vấn dữ liệu từ bảng alert
$sqlSelect = "SELECT * FROM alert";
$result = $conn->query($sqlSelect);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='edit-container'>
                <form method='post' action=''>
                    <input type='hidden' name='alertId' value='" . $row["id"] . "'>
                    <textarea class='center-textarea' name='newContent' placeholder='Nội dung mới' required>" . $row["content"] . "</textarea>
                    <button type='submit' name='edit' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Chỉnh sửa</button>
                </form>
            </div>";
    }
} else {
    echo "<div class='edit-container'>Không có dữ liệu</div>";
}
?>
<script>
    /*F5 không thực hiện lại lệnh*/
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</div>
</div>
<?php include_once './end.php'; ?>