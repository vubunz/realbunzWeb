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
$searchUsername = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchUsername = $_POST["username"];

    $sql = "SELECT players.id, players.name, users.status, users.kh, users.coin, users.luong, users.isVip, users.tongnap, users.ip_address, users.password, users.phone
            FROM players 
            JOIN users ON players.id = users.id
            WHERE users.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchUsername);
    if (checkAdmin($conn, $_SESSION['username'])) {
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $_SESSION['error'] = "Không đủ thẩm quyền!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }
}

ob_end_flush();
?>

<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    .custom-table th,
    .custom-table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #0096ff;
    }

    .custom-table tbody tr {
        border-bottom: 1px solid #0096ff;
    }

    .custom-table tbody tr:last-child {
        border-bottom: none;
    }

    .custom-table th:last-child,
    .custom-table td:last-child {
        border-right: none;
    }

    .custom-table thead th {
        background-color: #0096ff;
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .custom-table tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .most-common-ip {
        font-weight: bold;
    }

    .all-ip-addresses {
        display: none;
    }
</style>
<style>
    .form-signin {
        max-width: 300px;
        /* Điều chỉnh chiều rộng tối đa của ô tìm kiếm */
        margin: 0 auto;
        /* Canh giữa ô tìm kiếm */
    }

    .form-control {
        max-width: 100%;
        /* Chiều rộng tối đa của ô tìm kiếm */
    }
</style>
<style>
    .blink {
        animation: blinker 0.123456789s cubic-bezier(.5, 0, 1, 1) infinite alternate;
    }

    @keyframes blinker {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }
</style>

<div class="card">
    <div class="card-body1">
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>
        <form method="POST" class="form-signin" action="">
            <div class="mb-3 text-center">
                <br>
                <label class="font-weight-bold d-block"></label>
                <input type="text" class="form-control form-control-solid" name="username" id="username" placeholder="Nhập tên tài khoản cần tìm" required>
            </div>
            <div class="text-center mt-3">
                <button class="me-3 btn btn-success" type="submit" name="submit" id="btn">Tìm kiếm</button>
            </div>
        </form>

        <div id="result-container">
            <?php
            if (isset($result)) {
                if ($result->num_rows > 0) {
                    echo "<br>
                    <hr>
                    <br>";
                    $rowCount = 0;

                    while ($row = $result->fetch_assoc()) {
                        $rowCount++;

                        echo "<table class='table'>";
                        echo "<tbody>";
                        echo "<tr class='fw-semibold'><td>ID</td><td>" . $row["id"] . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Tài khoản</td><td>" . $searchUsername . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Nhân vật</td><td>" . $row["name"] . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Trạng thái</td><td>" . getActivatedDescription($row["kh"]) . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Tình trạng</td><td>" . getStatusDescription($row["status"]) . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Mật khẩu</td><td>" . $row["password"] . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Lượng</td><td style='color: red;'>" . number_format($row["luong"]) . " Lượng</td></tr>";
                        if ($row["isVip"] > 0) {
                            echo "<tr class='fw-semibold'><td class='blink' style='color: rgb(255, 102, 0);'></td><td class='blink' style='color: rgb(255, 102, 0);'>" . $row["isVip"] . "</td></tr>";
                        } else {
                            echo "<tr class='fw-semibold'><td>VIP(Đang phát triển)</td><td>" . $row["isVip"] . "</td></tr>";
                        }
                        echo "<tr class='fw-semibold'><td>Coin</td><td style='color: #ff6600;'>" . number_format($row["coin"]) . " Coin</td></tr>";
                        echo "<tr class='fw-semibold'><td>IP</td><td>";

                        $ipAddresses = explode(',', str_replace(['"', '[', ']'], '', $row["ip_address"]));
                        $ipCount = count($ipAddresses);
                        $showAllLinkStyle = ($ipCount > 1) ? 'inline-block' : 'none';
                        $mostCommonIP = ($ipCount > 1) ? key(array_count_values($ipAddresses)) : reset($ipAddresses);

                        echo "<span class='most-common-ip'>$mostCommonIP</span>";

                        if ($ipCount > 1) {
                            //                echo "<a class='cursor-pointer text-primary show-all-ip' style='display:$showAllLinkStyle;'><span> </span>Hiển thị Tất cả</a>";
                            echo "<a class='cursor-pointer text-primary show-all-ip' style='display:$showAllLinkStyle;'>&nbsp;Hiển thị Tất cả</a>";

                            echo "<a class='cursor-pointer text-primary hide-all-ip' style='display:none;'>&nbsp;Ẩn bớt</a>";

                            echo "<div class='all-ip-addresses'>";
                            foreach ($ipAddresses as $ip) {
                                echo "<div>$ip</div>";
                            }
                        }

                        echo "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Đã Nạp</td><td>" . number_format($row["tongnap"]) . "</td></tr>";
                        echo "<tr class='fw-semibold'><td>Số điện thoại</td><td>" . $row["phone"] . "</td></tr>";
                        echo "</tbody>";
                        echo "</table>";

                        if ($rowCount < $result->num_rows) {
                            echo "<hr>";
                        }
                    }
                } else {
                    echo "<p>Không tìm thấy nhân vật nào cho tài khoản '$searchUsername'</p>";
                }

                $stmt->close();
            }

            function getStatusDescription($status)
            {
                $color = ($status == 1) ? "green" : "red";
                return "<span style='color: $color;'>" . ($status == 1 ? "Bình thường" : "Đang bị khoá") . "</span>";
            }

            function getActivatedDescription($activated)
            {
                $color = ($activated == 1) ? "green" : "red";
                return "<span style='color: $color;'>" . ($activated == 1 ? "Đã kích hoạt" : "Chưa kích hoạt") . "</span>";
            }


            ?>
        </div>

        <input type="text" id="clipboard-input" style="position: absolute; left: -9999px;">

        <script>
            function copyToClipboard(content) {
                var clipboardInput = document.getElementById("clipboard-input");
                clipboardInput.value = content;
                clipboardInput.select();
                document.execCommand("copy");
            }

            document.addEventListener('DOMContentLoaded', function() {
                var showAllLinks = document.querySelectorAll('.show-all-ip');
                var hideAllLinks = document.querySelectorAll('.hide-all-ip');
                var allIPContainers = document.querySelectorAll('.all-ip-addresses');

                showAllLinks.forEach(function(showLink, index) {
                    showLink.addEventListener('click', function() {
                        allIPContainers[index].style.display = 'block';
                        showLink.style.display = 'none';
                        hideAllLinks[index].style.display = 'inline-block';
                    });
                });

                hideAllLinks.forEach(function(hideLink, index) {
                    hideLink.addEventListener('click', function() {
                        allIPContainers[index].style.display = 'none';
                        hideLink.style.display = 'none';
                        showAllLinks[index].style.display = 'inline-block';
                    });
                });
            });
        </script>
    </div>
</div>

<?php
include_once './end.php';
?>