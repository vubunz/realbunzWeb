<?php
require_once("CMain/connect.php");

// Kiểm tra action và thực hiện tương ứng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    switch ($action) {
        case "verifyOTP":
            verifyOTP($conn);
            break;
        case "resetPassword":
            resetPassword($conn);
            break;
        default:
            // Xử lý trường hợp không xác định action
            break;
    }
}

function verifyOTP($conn) {
    $username = $_POST["fusername"];
    $otp = $_POST["fcode"];

    // Thực hiện truy vấn SQL để kiểm tra OTP
    $sql = "SELECT * FROM player WHERE username = '$username' AND matkhaucap2 = '$otp'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Mã OTP đúng, có thể cho phép người dùng đặt lại mật khẩu
        echo json_encode(["code" => "00"]);
    } else {
        // Mã OTP không đúng
        echo json_encode(["code" => "01"]);
    }
}

function resetPassword($conn) {
    $username = $_POST["fusername"];
    $newPassword = $_POST["fnewpassword"];

    // Random số 4 hoặc 5 chữ số
    $otp = rand(1000, 99999);

    // Thực hiện truy vấn SQL để cập nhật mật khẩu mới và mã OTP
    $sql = "UPDATE player SET password = '$newPassword', matkhaucap2 = '$otp' WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["code" => "00"]);
    } else {
        echo json_encode(["code" => "01"]);
    }
}


// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>
