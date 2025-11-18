<?php
require_once("CMain/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    $username = strip_tags($username);
    $username = addslashes($username);
    $password = strip_tags($password);
    $password = addslashes($password);

    if ($username == "" || $password == "") {
        echo '{"code": "01", "text": "Thông tin tài khoản hoặc mật khẩu không được để trống."}';
    } else {
        $sql = "SELECT `username`, `password` FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
        $query = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($query);
        $maskedPassword = str_repeat('*', strlen($password));
        $formattedTime = date("F j, Y, g:i a");

        if ($num_rows == 0) {
            echo '{"code": "02", "text": "Thông tin tài khoản hoặc mật khẩu không chính xác :((."}';
        } else {
            // Đăng nhập thành công, ghi log
            $log  = "Host: " . $_SERVER['REMOTE_ADDR'] . ' - ' . $formattedTime . PHP_EOL .
                "Content LOGIN: " . json_encode(["username" => $username, "password" => $maskedPassword]) . PHP_EOL .
                "-------------------------" . PHP_EOL;
            file_put_contents('./lichsu/login/logs_' . date("j.n.Y") . '.log', $log, FILE_APPEND);

            // Lưu thông tin đăng nhập vào session
            session_start();
            $_SESSION['username'] = $username;

            echo '{"code": "00", "text": "Đăng nhập thành công."}';
        }
    }
}
