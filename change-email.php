<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './main.php';


require_once("CMain/connect.php");
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

// Xử lý AJAX
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (
        (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
        || (isset($_POST['action']) && in_array($_POST['action'], ['send_otp', 'change_email', 'set_email']))
        || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    )
) {
    // Dừng mọi output buffer trước đó
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json; charset=UTF-8');
    $data = json_decode(file_get_contents('php://input'), true);
    $action = isset($data['action']) ? $data['action'] : '';

    if (!isset($_SESSION['username'])) {
        echo json_encode(['code' => '99', 'text' => 'Bạn chưa đăng nhập!']);
        exit;
    }
    $username = $_SESSION['username'];

    if ($action == 'send_otp') {
        // Giới hạn 60s/account
        if (isset($_SESSION['change_email_otp_last_time']) && time() - $_SESSION['change_email_otp_last_time'] < 60) {
            $wait = 60 - (time() - $_SESSION['change_email_otp_last_time']);
            echo json_encode(['code' => '06', 'text' => "Bạn phải chờ $wait giây nữa mới được lấy lại mã OTP!"]);
            exit;
        }
        $stmt = $conn->prepare("SELECT email FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $email = $row['email'];
            if (empty($email)) {
                echo json_encode(['code' => '02', 'text' => 'Tài khoản chưa có email cũ!']);
                exit;
            }
            $otp = rand(10000, 99999);
            $_SESSION['change_email_otp'] = $otp;
            $_SESSION['change_email_otp_time'] = time();
            $_SESSION['change_email_otp_last_time'] = time();

            try {
                $mail = new PHPMailer(true);
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'hotro.nsolegacy@gmail.com'; // Thay bằng Gmail của bạn
                $mail->Password = 'lpyy jhtp pikr hpjo';    // Thay bằng App Password 16 ký tự
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('hotro.nsolegacy@gmail.com', 'NINJA LEGACY - Hỗ trợ tài khoản');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Mã xác thực đổi email NINJA LEGACY';
                $mail->Body = "Mã OTP xác thực đổi email của bạn là: <b>$otp</b><br>Mã có hiệu lực trong 5 phút.";
                $mail->send();
                echo json_encode(['code' => '00', 'text' => 'Đã gửi mã OTP về email: ' . $email . '!']);
            } catch (Exception $e) {
                // Log lỗi để debug (có thể xóa sau)
                error_log("PHPMailer Error: " . $e->getMessage());
                error_log("Email: " . $email . ", Username: " . $username);
                echo json_encode(['code' => '07', 'text' => 'Không thể gửi email, vui lòng thử lại sau! Lỗi: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['code' => '03', 'text' => 'Không tìm thấy tài khoản!']);
        }
        $stmt->close();
        exit;
    }

    if ($action == 'change_email') {
        $new_email = isset($data['new_email']) ? $data['new_email'] : '';
        $otp = isset($data['otp']) ? $data['otp'] : '';
        if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['code' => '01', 'text' => 'Email mới không hợp lệ!']);
            exit;
        }
        if (empty($otp) || !isset($_SESSION['change_email_otp']) || !isset($_SESSION['change_email_otp_time'])) {
            echo json_encode(['code' => '02', 'text' => 'Bạn chưa lấy mã OTP!']);
            exit;
        }
        if (time() - $_SESSION['change_email_otp_time'] > 300) { // 5 phút
            unset($_SESSION['change_email_otp']);
            unset($_SESSION['change_email_otp_time']);
            echo json_encode(['code' => '03', 'text' => 'Mã OTP đã hết hạn!']);
            exit;
        }
        if ($otp != $_SESSION['change_email_otp']) {
            echo json_encode(['code' => '04', 'text' => 'Mã OTP không đúng!']);
            exit;
        }
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_email, $username);
        if ($stmt->execute()) {
            unset($_SESSION['change_email_otp']);
            unset($_SESSION['change_email_otp_time']);
            unset($_SESSION['change_email_otp_last_time']);
            echo json_encode(['code' => '00', 'text' => 'Đổi email thành công!']);
        } else {
            echo json_encode(['code' => '05', 'text' => 'Lỗi hệ thống, vui lòng thử lại!']);
        }
        $stmt->close();
        exit;
    }

    if ($action == 'set_email') {
        $set_email = isset($data['set_email']) ? $data['set_email'] : '';
        if (empty($set_email) || !filter_var($set_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['code' => '01', 'text' => 'Email không hợp lệ!']);
            exit;
        }
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
        $stmt->bind_param("ss", $set_email, $username);
        if ($stmt->execute()) {
            echo json_encode(['code' => '00', 'text' => 'Lưu email thành công!']);
        } else {
            echo json_encode(['code' => '05', 'text' => 'Lỗi hệ thống, vui lòng thử lại!']);
        }
        $stmt->close();
        exit;
    }

    echo json_encode(['code' => '98', 'text' => 'Yêu cầu không hợp lệ!']);
    exit;
}

$stmt = $conn->prepare("SELECT email FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$email = $row['email'];
$stmt->close();
?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title text-center">Đổi email tài khoản</h5>
        <?php if (empty($email)): ?>
            <!-- Nếu chưa có email, chỉ hiện form nhập email cũ -->
            <form id="setEmailForm" method="POST" class="pb-3" style="width: 26rem; margin: 0 auto;">
                <div class="mb-3">
                    <input type="email" class="form-control" name="set_email" id="set_email" placeholder="Nhập email cho tài khoản" required>
                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                </div>
                <div class="text-center mt-3 d-flex justify-content-center">
                    <button class="me-3 btn btn-success" type="submit" id="setEmailBtn">Xác nhận email</button>
                </div>
                <div id="setEmailMsg" class="mt-3"></div>
            </form>
        <?php else: ?>
            <!-- Nếu đã có email, hiện form đổi email như cũ -->
            <form id="changeEmailForm" method="POST" class="pb-3" style="width: 26rem; margin: 0 auto;">
                <div class="mb-3">
                    <input type="email" class="form-control" name="new_email" id="new_email" placeholder="Nhập email mới" required>
                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                </div>
                <div class="mb-3 d-flex">
                    <input type="text" class="form-control me-2" name="otp" id="otp" placeholder="Nhập mã OTP" required>
                    <button type="button" class="btn btn-secondary" id="getOtpBtn">Lấy mã OTP</button>
                </div>
                <div class="invalid-feedback" id="otpError" style="display:none;">Mã OTP không hợp lệ hoặc đã hết hạn.</div>
                <div class="text-center mt-3 d-flex justify-content-center">
                    <button class="me-3 btn btn-success" type="submit" id="confirmChangeBtn">Xác nhận đổi email</button>
                </div>
                <div id="changeEmailMsg" class="mt-3"></div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        // Lấy mã OTP
        $('#getOtpBtn').on('click', function() {
            let btn = $(this);
            let msg = $('#changeEmailMsg');
            btn.prop('disabled', true);
            msg.html('');
            $.ajax({
                url: 'change-email.php',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    action: 'send_otp'
                }),
                success: function(res) {
                    if (res.code === '00') {
                        msg.html('<div class="alert alert-success">' + res.text + '</div>');
                    } else if (res.code === '06') {
                        msg.html('<div class="alert alert-warning">' + res.text + '</div>');
                        btn.prop('disabled', false);
                    } else if (res.code === '07') {
                        msg.html('<div class="alert alert-danger">' + res.text + '</div>');
                        btn.prop('disabled', false);
                    } else {
                        msg.html('<div class="alert alert-danger">' + res.text + '</div>');
                        btn.prop('disabled', false);
                    }
                    let count = 60;
                    btn.text('Gửi lại (' + count + 's)');
                    let interval = setInterval(function() {
                        count--;
                        btn.text('Gửi lại (' + count + 's)');
                        if (count <= 0) {
                            clearInterval(interval);
                            btn.text('Lấy mã OTP');
                            btn.prop('disabled', false);
                        }
                    }, 1000);
                },
                error: function(xhr) {
                    console.log('ERROR:', xhr.responseText);
                    try {
                        var data = JSON.parse(xhr.responseText);
                        if (data.code === '00') {
                            $('#changeEmailMsg').html('<div class="alert alert-success">' + data.text + '</div>');
                            return;
                        }
                    } catch (e) {
                        // Không phải JSON hợp lệ
                    }
                    let msg = "Có lỗi xảy ra, vui lòng thử lại sau.";
                    if (xhr.responseText) {
                        msg += "<br>" + xhr.responseText;
                    }
                    $('#changeEmailMsg').html('<div class="alert alert-danger">' + msg + '</div>');
                    btn.prop('disabled', false);
                }
            });
        });

        // Xác nhận đổi email
        $('#changeEmailForm').on('submit', function(e) {
            e.preventDefault();
            let new_email = $('#new_email').val().trim();
            let otp = $('#otp').val().trim();
            let msg = $('#changeEmailMsg');
            msg.html('');
            if (new_email.length === 0 || !/^[\w.-]+@([\w-]+\.)+[\w-]{2,4}$/.test(new_email)) {
                $('#new_email').addClass('is-invalid');
                return;
            } else {
                $('#new_email').removeClass('is-invalid');
            }
            if (otp.length === 0) {
                $('#otp').addClass('is-invalid');
                return;
            } else {
                $('#otp').removeClass('is-invalid');
            }
            $('#confirmChangeBtn').prop('disabled', true);
            $.ajax({
                url: 'change-email.php',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    action: 'change_email',
                    new_email: new_email,
                    otp: otp
                }),
                success: function(res) {
                    if (res.code === '00') {
                        msg.html('<div class="alert alert-success">' + res.text + '</div>');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        msg.html('<div class="alert alert-danger">' + res.text + '</div>');
                    }
                },
                error: function(xhr) {
                    console.log('ERROR:', xhr.responseText);
                    try {
                        var data = JSON.parse(xhr.responseText);
                        if (data.code === '00') {
                            $('#changeEmailMsg').html('<div class="alert alert-success">' + data.text + '</div>');
                            return;
                        }
                    } catch (e) {
                        // Không phải JSON hợp lệ
                    }
                    let msg = "Có lỗi xảy ra, vui lòng thử lại sau.";
                    if (xhr.responseText) {
                        msg += "<br>" + xhr.responseText;
                    }
                    $('#changeEmailMsg').html('<div class="alert alert-danger">' + msg + '</div>');
                },
                complete: function() {
                    $('#confirmChangeBtn').prop('disabled', false);
                }
            });
        });

        // Xác nhận email cũ
        $('#setEmailForm').on('submit', function(e) {
            e.preventDefault();
            let set_email = $('#set_email').val().trim();
            let msg = $('#setEmailMsg');
            msg.html('');
            if (set_email.length === 0 || !/^[\w.-]+@([\w-]+\.)+[\w-]{2,4}$/.test(set_email)) {
                $('#set_email').addClass('is-invalid');
                return;
            } else {
                $('#set_email').removeClass('is-invalid');
            }
            $.ajax({
                url: 'change-email.php',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    action: 'set_email',
                    set_email: set_email
                }),
                success: function(res) {
                    if (res.code === '00') {
                        msg.html('<div class="alert alert-success">' + res.text + '</div>');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        msg.html('<div class="alert alert-danger">' + res.text + '</div>');
                    }
                },
                error: function() {
                    msg.html('<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại sau.</div>');
                }
            });
        });
    });
</script>