<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("CMain/connect.php");
require __DIR__ . '/vendor/autoload.php'; // Đường dẫn autoload của Composer

$data = json_decode(file_get_contents('php://input'), true);
$username = isset($data['username']) ? $data['username'] : '';

if (empty($username)) {
    echo json_encode(['code' => '01', 'text' => 'Tên đăng nhập không hợp lệ.']);
    exit;
}

$stmt = $conn->prepare("SELECT email, password FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $password = $row['password'];
    if (empty($email)) {
        echo json_encode(['code' => '04', 'text' => 'Tài khoản này chưa khai báo email, không thể gửi lại mật khẩu.']);
        exit;
    }

    // Cấu hình PHPMailer gửi qua Gmail SMTP
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8'; // Đặt charset UTF-8 để không lỗi font
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hotro.nsolegacy@gmail.com'; // Thay bằng Gmail của bạn
        $mail->Password   = 'lpyy jhtp pikr hpjo';    // Thay bằng App Password 16 ký tự
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Thông tin người gửi
        $mail->setFrom('hotro.nsolegacy@gmail.com', 'NINJA LEGACY');
        $mail->addAddress($email);

        $mail->isHTML(true); // Gửi HTML mail cho đẹp
        $mail->Subject = 'Khôi phục mật khẩu tài khoản NINJA LEGACY';
        $mail->Body = '
<div style="background:#f6f6f6;padding:0;margin:0;font-family:Arial,sans-serif;">
  <div style="max-width:500px;margin:40px auto;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:hidden;">
    <div style="text-align:center;padding:32px 0 0 0;">
      <img src="https://res.cloudinary.com/dcivynist/image/upload/v1751214428/lego_t6ywou.png" alt="NINJA LEGACY" style="height:48px;">
    </div>
    <div style="padding:0 32px 32px 32px;">
      <h2 style="color:#222;text-align:center;margin-top:24px;margin-bottom:8px;font-size:24px;font-weight:700;">Khôi phục mật khẩu</h2>
      <div style="text-align:center;margin-bottom:24px;">
        <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" alt="Reset Password" style="height:56px;margin:16px 0;">
      </div>
      <p style="text-align:center;font-size:16px;color:#222;"><b>Xin chào ' . htmlspecialchars($username) . ',</b></p>
      <p style="text-align:center;font-size:15px;color:#444;">
        Bạn hoặc ai đó vừa yêu cầu khôi phục mật khẩu cho tài khoản <b>NINJA LEGACY</b>.<br>
        Dưới đây là thông tin tài khoản của bạn:
      </p>
      <div style="background:#f2f2f2;border-radius:6px;padding:18px 0;margin:24px 0;text-align:center;">
        <div style="font-size:16px;color:#222;margin-bottom:8px;"><b>Tên đăng nhập:</b> ' . htmlspecialchars($username) . '</div>
        <div style="font-size:16px;color:#222;"><b>Mật khẩu:</b> ' . htmlspecialchars($password) . '</div>
      </div>
      <p style="text-align:center;font-size:14px;color:#888;">
        Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.<br>
        Nếu cần hỗ trợ, hãy liên hệ đội ngũ hỗ trợ của chúng tôi.
      </p>
      <hr style="margin:32px 0 16px 0;border:none;border-top:1px solid #eee;">
      <div style="text-align:center;font-size:13px;color:#aaa;">
        <b>Website:</b> <a href="https://nsolegacy.io.vn/" style="color:#2d9cdb;text-decoration:none;">nsolegacy.io.vn</a> |
        <b>Facebook:</b> <a href="https://www.facebook.com/profile.php?id=61577114496898" style="color:#2d9cdb;text-decoration:none;">NINJA LEGACY</a>
      </div>
      <div style="text-align:center;font-size:12px;color:#bbb;margin-top:16px;">
        &copy; ' . date('Y') . ' NINJA LEGACY.
      </div>
    </div>
  </div>
</div>
';
        $mail->AltBody = "Tên đăng nhập: $username\nMật khẩu: $password\nNINJA LEGACY";

        $mail->send();
        echo json_encode(['code' => '00', 'text' => 'Mật khẩu đã được gửi về email của bạn.']);
    } catch (Exception $e) {
        echo json_encode(['code' => '02', 'text' => 'Không gửi được email. Lỗi: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['code' => '03', 'text' => 'Tên đăng nhập không tồn tại trên hệ thống.']);
}
$stmt->close();
