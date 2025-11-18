<?php
require_once("CMain/connect.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "verifyOTP") {
        $username = $_POST["fusername"];
        $otp = $_POST["fcode"];

        $sql = "SELECT * FROM player WHERE username = '$username' AND matkhaucap2 = '$otp'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $response = array("code" => "00", "message" => "Mã OTP hợp lệ");
        } else {
            $response = array("code" => "01", "message" => "Mã OTP không đúng");
        }

        echo json_encode($response);
    }
}

$conn->close();
?>
