
<?php

//require_once("CMain/connect.php");

$data = json_decode(file_get_contents('php://input'), true);
$choose = $data['pcoin'];

try {
    session_start();

    // Lấy tên người dùng hiện tại từ session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        throw new Exception("Người dùng không tồn tại trong session");
    }

    // Kết nối CSDL
    require_once("CMain/connect.php");

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die('Kết nối CSDL thất bại: ' . $conn->connect_error);
    }

    // Thực hiện prepared statement
    $sqlUs = 'SELECT username FROM users WHERE username = ? LIMIT 1';
    $stmt = $conn->prepare($sqlUs);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $resultUs = $stmt->get_result();
    $stmt->close();

    // Kiểm tra lỗi và số dòng kết quả
    if ($resultUs === false) {
        die('Truy vấn SQL thất bại: ' . mysqli_error($conn));
    }

    $numRows = mysqli_num_rows($resultUs);

    // Đóng kết nối CSDL
    $conn->close();

    if ($numRows > 0) {
        if (!isset($list_recharge_price_momo[$choose])) {
            echo '{"code": "01", "text": "Bạn chưa chọn số tiền nạp"}';
        } elseif (isset($list_recharge_price_momo[$choose]) && isset($configNapTien['atm']['sotaikhoan'])) {
            $amount = $list_recharge_price_momo[$choose]['amount'];
            $acctNum = $configNapTien['atm']['sotaikhoan'];
            $qr = getQrMomoPayment($username, $amount, $acctNum);
            $link = getLinkMomoPayment($username, $amount, $acctNum);

            $namebank = $configNapTien['atm']['chutaikhoan'];
            $nhbank = $configNapTien['atm']['nganhang'];

            $qr = getQrAtmPayment($username, $amount, $acctNum);
            $gia = getgiaAtmPayment($amount);


            echo '{"code": "00", "text": "Lấy thông tin thanh toán thành công.", "qr_atm": "' . $qr . '", "gia_pay": "' . $gia . '", "name_pay": "' . $namebank . '", "stk_pay": "' . $acctNum . '", "nh_pay": "' . $nhbank . '"}';

            //echo '{"code": "00", "text": "Lấy thông tin thanh toán thành công.", "qr_pay": "'.$qr.'", "link_pay": "'.$link.'"}';
            return;
        }
    } else {
        echo '{"code": "01", "text": "Người dùng không tồn tại"}';
    }
} catch (Exception $e) {
    echo '{"code": "99", "text": "Hệ thống gặp lỗi. Vui lòng liên hệ quản trị viên để được hỗ trợ."}';
}
?>
