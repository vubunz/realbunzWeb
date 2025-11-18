<?php

require 'vendor/autoload.php';

require_once("CMain/connect.php");

// Nhận email từ form
$email = $_POST['email'];

// Truy vấn kiểm tra email trong bảng "users"
$sql = "SELECT phone, matkhaucap2 FROM player WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $smsCode = $row['matkhaucap2'];

    // Gửi email sử dụng SwiftMailer
    sendEmail($email, $smsCode);

    echo json_encode(array("message" => "Mã đã được gửi đến email của bạn."));
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Email không tồn tại."));
}

$conn->close();

function sendEmail($toEmail, $smsCode) {
    $transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
    $transport->setUsername('nsonoreply@gmail.com'); // Thay bằng địa chỉ email của bạn
    $transport->setPassword('@Nso25251325'); // Thay bằng mật khẩu email của bạn

    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message('Xác nhận lấy lại mật khẩu');
    $message->setFrom(['nsonoreply@gmail.com' => 'ADMIN']); // Thay bằng địa chỉ email và tên của bạn
    $message->setTo([$toEmail]);
    $message->setBody("Mã xác nhận của bạn là: $smsCode", 'text/html');

    $result = $mailer->send($message);

    if ($result) {
        echo 'Email đã được gửi thành công.';
    } else {
        echo 'Gửi email thất bại.';
    }
}
?>
