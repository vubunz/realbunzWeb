<?php
require_once("CMain/connect.php");

$data = json_decode(file_get_contents('php://input'), true);

$username = isset($data['username']) ? $data['username'] : '';
$password = isset($data['password']) ? $data['password'] : '';
$email = isset($data['phone']) ? $data['phone'] : '';
$created_at = date("Y-m-d H:i:s");

try {
    // Kiểm tra xem tên đăng nhập có chứa ký tự đặc biệt, ký tự hoa, hoặc khoảng trắng không
    if (!preg_match('/^[a-z0-9]+$/', $username)) {
        echo '{"code": "05", "text": "Tên đăng nhập không hợp lệ. Chỉ chấp nhận chữ cái thường và số."}';
        exit;
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $stmt = $conn->prepare("SELECT * FROM player WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result != false && $result->num_rows > 0) {
        // Tên đăng nhập đã tồn tại
        echo '{"code": "02", "text": "Tên đăng nhập đã tồn tại trên hệ thống."}';
    } else {
        // Kiểm tra xem Email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM player WHERE phone = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result != false && $result->num_rows > 1) {
            // Email đã tồn tại
            echo '{"code": "03", "text": "Số điện thoại đã tồn tại trên hệ thống."}';
        } else {
            // Thực hiện thêm mới người dùng với created_at và đặt giá trị "vip" là 1
            $stmt = $conn->prepare("INSERT INTO player (username, password, phone, created_at, vip) VALUES (?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("ssss", $username, $password, $email, $created_at);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $maskedPassword = str_repeat('*', strlen($password));
                $maskedEmail = str_repeat('*', strlen($email));
                $formattedTime = date("F j, Y, g:i a");
                $log  = "Host: ".$_SERVER['REMOTE_ADDR'].' - '.$formattedTime.PHP_EOL.
                        "Content REGISTER: ".json_encode(["username" => $username, "password" => $maskedPassword, "phone" => $maskedEmail]).PHP_EOL.
                        "-------------------------".PHP_EOL;
                file_put_contents('./lichsu/register/regs_'.date("j.n.Y").'.log', $log, FILE_APPEND);

                echo '{"code": "00", "text": "Đăng ký thành công."}';
            } else {
                // Có lỗi xảy ra trong quá trình đăng ký
                echo '{"code": "04", "text": "Có lỗi xảy ra. Vui lòng liên hệ ADMIN để được hỗ trợ."}';
            }
        }
    }

    // Đóng kết nối và statement
    $stmt->close();
   // $conn->close();
} catch (Exception $e) {
    echo '{"code": "99", "text": "Hệ thống gặp lỗi. Vui lòng liên hệ quản trị viên để được hỗ trợ."}';
}
?>
