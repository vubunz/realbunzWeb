<?php
$data = json_decode(file_get_contents('php://input'), true);

try {
    session_start();
    require_once("CMain/connect.php");

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die('{"code": "99", "text": "Kết nối cơ sở dữ liệu thất bại."}');
    }

    $username = $_SESSION['username'];

    // Định nghĩa $fees nếu chưa được định nghĩa trong connect.php của bạn
    $fees = ['active' => 10000]; // Điều chỉnh giá trị theo cần thiết

    $row_users = $conn->query("SELECT * FROM player WHERE username = '$username' LIMIT 1");

    if ($row_users != false && $row_users->num_rows > 0) {
        $user_renew = $row_users->fetch_assoc();

        if ($user_renew == null) {
            echo '{"code": "01", "text": "Thông tin tài khoản hoặc mật khẩu không chính xác."}';
            return;
        }

        if ($user_renew['coin'] < $fees['active']) {
            echo '{"code": "05", "text": "Tài khoản không đủ số dư."}';
            return;
        }

        $new_coin_balance = $user_renew['coin'] - $fees['active'];

        $update_query = "UPDATE player SET XacThuc = 1, coin = $new_coin_balance WHERE username = '$username'";
        $update_result = $conn->query($update_query);

        if ($update_result) {
            echo '{"code": "00", "text": "Kích hoạt tài khoản thành công."}';
        } else {
            // In ra lỗi MySQL khi có lỗi
            echo '{"code": "06", "text": "Kích hoạt tài khoản thất bại. Lỗi MySQL: ' . $conn->error . '"}';
        }
    } else {
        echo '{"code": "01", "text": "Thông tin tài khoản hoặc mật khẩu không chính xác."}';
    }

    // Đóng kết nối
    $conn->close();
} catch (Exception $e) {
    echo '{"code": "99", "text": "Hệ thống gặp lỗi. Vui lòng liên hệ quản trị viên để được hỗ trợ."}';
}
?>
