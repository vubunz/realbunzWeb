<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Hàm kiểm tra email và gửi mã
function sendCodeToEmail($email, $code, $conn)
{
    $timeLimit = 300; // Thời gian chờ giữa các lần gửi (5p)

    if (checkEmailSendingLimit($email, $timeLimit, $conn)) {
        $senderEmail = "dohongquan021@gmail.com";
        $appPassword = "Quan10032002@"; // Thay bằng mật khẩu ứng dụng của bạn

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $senderEmail;
            $mail->Password = $appPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($senderEmail);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8'; // Đặt encoding cho nội dung email
            $mail->Subject = '=?UTF-8?B?' . base64_encode('Mật Khẩu Cấp 2') . '?='; // Đặt encoding cho chủ đề email
            $mail->Body = "Mã xác nhận của bạn là: $code. Vui lòng không cung cấp mã cho bất kỳ ai, Mã chỉ có tác dụng trong 1 lần đổi mật khẩu.";

            $mail->send();

            // Log lại việc gửi mail
            logEmailSending($email, $conn);

            $updateSql = "UPDATE player SET matkhaucap2='$code' WHERE phone='$phone'";
            $conn->query($updateSql);

            return true;
        } catch (Exception $e) {
            // Ghi log lỗi
            error_log("Error sending email: {$mail->ErrorInfo}");
            return false;
        }
    } else {
        return false; // Không đủ thời gian chờ
    }
}

// Hàm kiểm tra số lần gửi mail trong khoảng thời gian
function checkEmailSendingLimit($phone, $timeLimit, $conn)
{
    $currentTime = time();

    // Truy vấn để lấy thời gian gần nhất mà mail đã được gửi
    $sql = "SELECT sent_time FROM phone_log WHERE phone = '$phone' ORDER BY sent_time DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastSentTime = strtotime($row['sent_time']);

        // Kiểm tra xem đã hết thời gian chờ chưa
        if ($currentTime - $lastSentTime < $timeLimit) {
            echo '{"code": "05", "text": "Bạn vừa thao tác gửi mã OTP rồi, Vui lòng thử lại sau ít phút."}';
           // return false; // Chưa đủ thời gian chờ
           exit();
        }
    }

    return true; // Có thể gửi mail
}

// Hàm ghi log khi gửi mail
function logEmailSending($phone, $conn)
{
    $currentTime = date('Y-m-d H:i:s');
    $sql = "INSERT INTO phone_log (phone, sent_time) VALUES ('$phone', '$currentTime')";
    $conn->query($sql);
}

require_once("CMain/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["fusername"]) ? $_POST["fusername"] : '';

    $username = strip_tags($username);
    $username = addslashes($username);

    if ($username == "") {
        echo '{"code": "01", "text": "Tên tài khoản không được để trống."}';
    } else {
        $sql = "SELECT phone FROM player WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Lấy email từ kết quả truy vấn
            $row = $result->fetch_assoc();
            $phone = $row['phone'];

            // Kiểm tra email và username trùng khớp
            if ($phone === $_POST["fphone"]) {
                // Tạo và gửi mã xác nhận
                $code = rand(1000, 9999);
                if (sendCodeToEmail($phone, $code, $conn)) {
                    echo '{"code": "00", "text": "Mã xác nhận đã được gửi đến phone của bạn và được cập nhật trong cơ sở dữ liệu."}';
                } else {
                    echo '{"code": "02", "text": "Có lỗi xảy ra khi gửi mã xác nhận."}';
                }
            } else {
                echo '{"code": "04", "text": "Tên tài khoản và phone không khớp."}';
            }
        } else {
            echo '{"code": "03", "text": "Tên tài khoản không tồn tại trong hệ thống."}';
        }
    }
}

$conn->close();
?>
