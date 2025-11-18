<?php
ob_start();
include './main.php';
if (!isset($_SESSION['username'])) {
    header('Location: /');
}
if (!checkAdmin($conn, $_SESSION['username'])) {
    header('Location: /');
    exit();
}
ob_end_flush();
?>
<style>
    .table {
        width: 100%;
    }

    .fw-semibold {
        font-weight: bold;
    }

    .table tbody tr.separator {
        height: 10px;
        background-color: transparent;
        border: none;
    }
</style>


<div class="card">
    <div class="card-body1">
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>
        <div class="w-100 d-flex justify-content-center">
            <form method="POST" class="form-signin" action="">
                <div class="mb-3 text-center">
                    <label class="font-weight-bold d-block">Tên nhân vật cần tìm</label>
                    <input type="text" class="form-control form-control-solid" name="playerName" id="playerName" placeholder="Nhập tên nhân vật cần tìm" required>
                </div>
                <div class="text-center mt-3">
                    <button class="me-3 btn btn-success" type="submit" name="submit" id="btn">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <?php
        $message = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (checkAdmin($conn, $_SESSION['username'])) {
                $searchTerm = $_POST["playerName"];
                $sql = "SELECT players.id, players.name, users.username, users.password, users.status, users.role, players.xu, players.yen, users.coin, users.luong, users.isVIP, users.tongnap
                    FROM players
                    JOIN users ON players.id = users.id
                    WHERE players.name = '$searchTerm'";
                $result = $conn->query($sql);
            } else {
                $_SESSION['error'] = "Không đủ thẩm quyền!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            }
            if ($result->num_rows > 0) {
                echo "
                    <br>
                    <hr>"; // Đưa đường kẻ ngang vào đây để chỉ xuất hiện khi có kết quả trả về.

                while ($row = $result->fetch_assoc()) {
                    // $ipAddresses = explode(',', str_replace(['"', '[', ']'], '', $row["ip_address"]));
                    // $ipCount = count($ipAddresses);

                    // Nếu chỉ có một địa chỉ IP, ẩn nút "Hiển thị Tất cả"
                    // $showAllLinkStyle = ($ipCount > 1) ? 'inline-block' : 'none';

                    // $mostCommonIP = ($ipCount > 1) ? key(array_count_values($ipAddresses)) : reset($ipAddresses);

                    echo "<style>
                        .xu-row td {
                            color: #0096ff; /*xu*/
                        }
                    
                        .yen-row td {
                            color: #00a2ff; /*yên*/
                        }
                    
                        .luong-row td {
                            color: red; /*Lượng */
                        }
                    
                        .coin-row td {
                            color: purple; /*Coin */
                        }

                        @keyframes blink {
                            0% {
                                color: rgb(255, 0, 0); /* Màu đỏ ban đầu */
                            }
                            50% {
                                color: rgb(0, 255, 0); /* Màu xanh lá cây giữa chừng */
                            }
                            100% {
                                color: rgb(255, 0, 0); /* Trở lại màu đỏ */
                             }
                         }
                    
                        .vip-row td {
                            animation: blink 2s infinite; /* Áp dụng hiệu ứng nhấp nháy trong 2 giây, lặp vô hạn */
                        }
                        @keyframes flash-nap {
                            0% {
                                color: rgb(255, 255, 255);
                            }
                            50% {
                                color: rgb(255, 0, 0);
                            }
                            100% {
                                color: rgb(255, 255, 255);
                            }
                        }
                    
                        .tongnap-row td {
                            animation: flash-nap 1.23456789s infinite;
                        }
                    </style>
                    <table class='table'>
                        <tbody>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold'>
                                <td>ID</td>
                                <td>" . $row["id"] . "</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold'>
                                <td>Nhân vật</td>
                                <td>" . $row["name"] . "</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold'>
                                <td>Tài khoản</td>
                                <td>" . $row["username"] . "</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold'>
                                <td>Mật khẩu</td>
                                <td>" . $row["password"] . "</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold xu-row'>
                                <td>Xu</td>
                                <td>" . number_format($row["xu"]) . " Xu</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold yen-row'>
                                <td>Yên</td>
                                <td>" . number_format($row["yen"]) . " Yên</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold luong-row'>
                                <td>Lượng</td>
                                <td>" . number_format($row["luong"]) . " Lượng</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold coin-row'>
                                <td>Coin</td>
                                <td>" . number_format($row["coin"]) . " Coin</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold vip-row'>
                                <td>VIP</td>
                                <td>" . $row["isVIP"] . "</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold'>
                                 <td>IP</td>
                                <td>
                                     <span class='most-common-ip'>/span>
                                    <a class='cursor-pointer text-primary show-all-ip' style='display:;'>Hiển thị Tất cả</a>
                                    <a class='cursor-pointer text-primary hide-all-ip' style='display:none;'>Ẩn bớt</a>
                                </td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold tongnap-row'>
                                <td>Đã Nạp</td>
                                <td>" . number_format($row["tongnap"]) . "</td>
                            </tr>
                            <tr class='separator'></tr>
                            <tr class='fw-semibold'>
                                <td>Số điện thoại</td>
                               
                            </tr>
                        </tbody>
                    </table>";

                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var showAllLink = document.querySelector('.show-all-ip');
                            var hideAllLink = document.querySelector('.hide-all-ip');
                            var ipContainer = document.querySelector('.most-common-ip');
                    
                            showAllLink.addEventListener('click', function () {
                               
                                showAllLink.style.display = 'none';
                                hideAllLink.style.display = 'inline-block';
                            });
                    
                            hideAllLink.addEventListener('click', function () {
                               
                                hideAllLink.style.display = 'none';
                                showAllLink.style.display = 'inline-block';
                            });
                        });
                    </script>";
                }
            } else {
                $message = "Không tìm thấy nhân vật.";
            }
        }

        echo $message;
        function getStatusDescription($status)
        {
            $color = ($status == 1) ? "red" : "#0096ff";
            return "<span style='color: $color;'>" . ($status == 1 ? "Đang bị khoá" : "Bình thường") . "</span>";
        }

        function getActivatedDescription($activated)
        {
            $color = ($activated == 1) ? "#0096ff" : "status";
            return "<span style='color: $color;'>" . ($activated == 1 ? "Đã kích hoạt" : "Chưa kích hoạt") . "</span>";
        }
        ?>
    </div>
</div>

<?php
include_once './end.php';
?>