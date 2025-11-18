<style>
    form {
        text-align: center;
    }

    table {
        margin-top: 20px;
        /* Thêm khoảng cách giữa form và bảng */
        border-collapse: collapse;
        /* Loại bỏ khoảng trắng giữa các ô trong bảng */
        width: 100%;
        /* Chiều rộng bảng là 100% */
    }

    th,
    td {
        border: 1px solid #ddd;
        /* Đặt đường biên cho các ô trong bảng */
        padding: 8px;
        /* Thêm khoảng trắng xung quanh nội dung của ô */
        text-align: left;
        /* Canh trái nội dung trong ô */
    }

    th {
        background-color: #f2f2f2;
        /* Màu nền cho các ô tiêu đề */
    }
</style>
<?php
ob_start();
include_once './main.php';
if (!isset($_SESSION['player'])) {
    header('Location: /');
}
if (!checkAdmin($conn, $_SESSION['player'])) {
    header('Location: /');
    exit();
}
ob_end_flush();
?>
<div class="card">
    <div class="card-body">
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>

        <form method="POST" action="">
            <label for="ipAddress">Nhập IP cần tìm:</label></br>
            <input type="text" name="ipAddress" id="ipAddress" placeholder="Địa chỉ IP" style="height: calc(1.5em + 15px);" required>
            <button class="btn btn-primary btn-sm" type="submit" name="search">Tìm kiếm</button>
        </form>

        <?php
        $ipAddress = "";
        $resultstatus = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["search"])) {
                if (!empty($_POST["ipAddress"])) {
                    $ipAddress = $_POST["ipAddress"];

                    if (checkAdmin($conn, $_SESSION['player'])) {
                        $sqlSearch = "SELECT id, player, coin, vip FROM users WHERE ip_address LIKE '%$ipAddress%'";
                        $sql = $conn->query($sqlSearch);
                    } else {
                        $_SESSION['error'] = "Không đủ thẩm quyền!";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                    }
                    if ($sql->num_rows > 0) {
                        echo "<br>";
                        echo "<h2 style='color: #0096ff;'>Các tài khoản có địa chỉ IP $ipAddress:</h2>";
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Tài khoản</th><th>Tình trạng</th></tr>";
                        while ($row = $sql->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["player"] . "</td>";
                            $activatedColor = ($row["status"] == 0 ? "red" : "#0096ff");
                            echo "<td style='color: $activatedColor;'>" . ($row["status"] == 1 ? "Bình thường" : "Bị khoá") . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "<form method='POST' action=''>";
                        echo "<br>";
                        echo "<button type='submit' name='status' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-success bg-opacity-25 border border-success border-opacity-75 rounded-2 link-success cursor-pointer'>UndBand theo IP</button>";
                        echo "&nbsp;";
                        echo "<button type='submit' name='unlock' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-danger bg-opacity-25 border border-danger border-opacity-75 rounded-2 link-success cursor-pointer'>Band theo IP</button>";
                        echo "<input type='hidden' name='foundIP' value='$ipAddress'>";
                        echo "</form>";
                    } else {
                        echo "<p>Không tìm thấy tài khoản nào với địa chỉ IP '$ipAddress'</p>";
                    }
                } else {
                    echo "<p>Cảnh báo: Biến 'ipAddress' không được xác định.</p>";
                }
            } elseif (isset($_POST["status"])) {
                if (!empty($_POST["foundIP"])) {
                    $ipAddress = $_POST["foundIP"];

                    // Thực hiện chức năng status
                    $sqlstatus = "UPDATE player SET status = 1 WHERE ip_address LIKE '%$ipAddress%'";
                    $resultstatus = $conn->query($sqlstatus);

                    if ($resultstatus) {
                        echo "<p style='color: red;'>Đã Band thành công tất cả các tài khoản có địa chỉ IP $ipAddress</p>";
                    } else {
                        echo "<p>Có lỗi xảy ra. Không thể Band tài khoản.</p>";
                    }
                } else {
                    echo "<p>Không có tài khoản nào để Band.</p>";
                }
            } elseif (isset($_POST["unlock"])) {
                if (!empty($_POST["foundIP"])) {
                    $ipAddress = $_POST["foundIP"];

                    // Thực hiện chức năng unlock
                    $sqlUnlock = "UPDATE users SET status = 0 WHERE ip_address LIKE '%$ipAddress%'";
                    $resultUnlock = $conn->query($sqlUnlock);

                    if ($resultUnlock) {
                        echo "<p style='color: #0096ff;'>Đã mở khóa thành công tất cả các tài khoản có địa chỉ IP $ipAddress</p>";
                    } else {
                        echo "<p>Có lỗi xảy ra. Không thể mở khóa tài khoản.</p>";
                    }
                } else {
                    echo "<p>Không có tài khoản nào để mở khóa.</p>";
                }
            }
        }

        ?>


        <script>
            /*F5 không thực hiện lại lệnh*/
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </div>
</div>
<?php include_once './end.php'; ?>