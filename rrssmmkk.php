<?php

// Hàm kết nối đến cơ sở dữ liệu
function connectDB() {
    require_once("CMain/connect.php");
    return $conn;
}

// Kiểm tra xem có dữ liệu được gửi từ phía client không
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Kiểm tra xem action có phải là "verifyOTP" không
    if (isset($_POST["action"]) && $_POST["action"] === "verifyOTP") {

        // Lấy dữ liệu từ phía client
        $username = $_POST["fusername"];
        $otp = $_POST["fcode"];

        // Kết nối đến cơ sở dữ liệu
        $conn = connectDB();

        // Kiểm tra xem username có tồn tại không
        $sql = "SELECT * FROM player WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Lấy thông tin người dùng từ kết quả truy vấn
            $row = $result->fetch_assoc();
            
            // So sánh OTP
            if ($otp == $row["matkhaucap2"]) {
                // Mã OTP đúng, trả về mã code "00"
                $response = array("code" => "00", "message" => "Xác minh OTP thành công");
            } else {
                // Mã OTP không đúng, trả về mã code "01"
                $response = array("code" => "01", "message" => "Mã OTP không đúng");
            }
        } else {
            // Username không tồn tại, trả về mã code "02"
            $response = array("code" => "02", "message" => "Tên đăng nhập không tồn tại");
        }

        // Đóng kết nối đến cơ sở dữ liệu
        $conn->close();

        // Trả về kết quả dưới dạng JSON
        echo json_encode($response);
        exit;
    }
}

// Nếu không có dữ liệu hợp lệ hoặc không có action phù hợp, trả về lỗi
$response = array("code" => "99", "message" => "Yêu cầu không hợp lệ");
echo json_encode($response);
exit;

?>
