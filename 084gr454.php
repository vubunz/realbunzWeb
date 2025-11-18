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
<div class="card">
    <div class="card-body">
        <center>
            <h2 style="color: black;">Buff Sạch not (Bẩn)</h2>
        </center>
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>
        <div class='edit-container'>
            <form method='post' action=''>
                <input type='text' name='searchName' placeholder='Nhập tên để tìm kiếm' required>
                <button type='submit' name='search' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Tìm kiếm</button>
            </form>
        </div>

        <?php
        // Kiểm tra kết nối cơ sở dữ liệu
        if ($conn->connect_error) {
            die("Kết nối không thành công: " . $conn->connect_error);
        }

        // Xử lý form tìm kiếm và sửa đổi nội dung
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (checkAdmin($conn, $_SESSION['username'])) {
                if (isset($_POST["search"])) {
                    $searchName = $_POST["searchName"];

                    // Truy vấn dữ liệu từ bảng players
                    $sqlSelect = "SELECT * FROM players WHERE name = '$searchName'";
                    $result = $conn->query($sqlSelect);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='edit-container'>
                        <button onclick='scrollToBottom()' class='scroll-button mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Xuống cuối trang</button>
                      </div><br>";
                            echo "<div class='edit-container'>
                                <form method='post' action=''>
                                    <input type='hidden' name='ninjaId' value='" . $row["id"] . "'>";

                            // Hiển thị thông tin của tất cả các cột
                            foreach ($row as $columnName => $columnValue) {
                                echo "<div class='form-group'>
                                    <label for='$columnName'>$columnName:</label>";

                                //echo "<textarea class='form-control center-textarea' name='$columnName' required>$columnValue</textarea>";
                                echo "<textarea class='form-control center-textarea' name='$columnName'>$columnValue</textarea>";

                                echo "</div>";
                            }

                            echo "<br><button type='submit' name='edit' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Lưu Lại</button>
                                </form>
                            </div>";
                            echo "<br><div class='edit-container'>
                        <button onclick='scrollToTop()' class='scroll-button mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Lên đầu trang</button>
                      </div>";

                            // Ghi log với thông tin chi tiết về sự thay đổi
                            $logContent = "{'username':'" . $_SESSION['username'] . "','action':'view_player','ip_address':'" . $_SERVER['REMOTE_ADDR'] . "','viewed_player_id':'" . $row["id"] . "'}";
                            writeToLog($logContent);
                        }
                    } else {
                        echo "<div class='edit-container'>Không có dữ liệu cho tên: $searchName</div>";
                        echo "<div class='edit-container'>
                            <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                          </div>";
                    }
                }

                if (isset($_POST["edit"])) {
                    $playersId = $_POST["ninjaId"];

                    // Lấy giá trị cũ của các cột
                    $sqlSelectOld = "SELECT * FROM players WHERE id = $playersId";
                    $resultOld = $conn->query($sqlSelectOld);

                    $oldValues = array();
                    if ($resultOld->num_rows > 0) {
                        $rowOld = $resultOld->fetch_assoc();
                        $oldValues = $rowOld;
                    }

                    // Tạo mảng để lưu giá trị mới của các cột
                    $newValues = array();
                    foreach ($_POST as $key => $value) {
                        if ($key != "ninjaId" && $key != "edit") {
                            // Chỉ thêm giá trị mới nếu có sự thay đổi
                            if ($oldValues[$key] != $value) {
                                $newValues[$key] = $value;
                            }
                        }
                    }

                    // Kiểm tra xem có sự thay đổi không
                    if (!empty($newValues)) {
                        // Kiểm tra trùng tên trong cơ sở dữ liệu
                        if (isset($newValues['name'])) {
                            $newName = $newValues['name'];
                            $checkDuplicateName = "SELECT id FROM players WHERE name = '$newName' AND id != $playersId";
                            $resultDuplicateName = $conn->query($checkDuplicateName);

                            if ($resultDuplicateName->num_rows > 0) {
                                echo "Lỗi: Tên '$newName' đã tồn tại trong cơ sở dữ liệu.";
                                echo "<div class='edit-container'>
                                    <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                                  </div>";
                                exit; // Dừng xử lý tiếp theo
                            }
                        }

                        // Kiểm tra trùng id trong cơ sở dữ liệu
                        if (isset($newValues['id'])) {
                            $newId = $newValues['id'];
                            $checkDuplicateId = "SELECT id FROM ninja WHERE id = $newId AND id != $playersId";
                            $resultDuplicateId = $conn->query($checkDuplicateId);

                            if ($resultDuplicateId->num_rows > 0) {
                                echo "Lỗi: ID '$newId' đã tồn tại trong cơ sở dữ liệu.";
                                echo "<div class='edit-container'>
                                    <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                                  </div>";
                                exit; // Dừng xử lý tiếp theo
                            }
                        }

                        // Tạo chuỗi SET cho truy vấn UPDATE
                        $setClause = "";
                        foreach ($newValues as $columnName => $columnValue) {
                            $setClause .= "$columnName = '$columnValue', ";
                        }
                        // Loại bỏ dấu phẩy cuối cùng
                        $setClause = rtrim($setClause, ', ');

                        $sqlUpdate = "UPDATE players SET $setClause WHERE id = $playersId";

                        if ($conn->query($sqlUpdate) === TRUE) {
                            // Lấy địa chỉ IP của người dùng
                            $ipAddress = $_SERVER['REMOTE_ADDR'];

                            // Hiển thị thông báo bằng mã JavaScript
                            echo "<script>
                            if ('$ipAddress') {
                                var notification = document.createElement('div');
                                notification.innerHTML = 'Lưu thay đổi thành công';
                                notification.classList.add('notification');
                                document.body.appendChild(notification);

                                setTimeout(function () {
                                    notification.style.opacity = '0';
                                    setTimeout(function () {
                                        document.body.removeChild(notification);
                                    }, 1000);
                                }, 2345);
                            }
                        </script>";

                            // Ghi log với thông tin chi tiết về sự thay đổi
                            $logContent = "{'username':'" . $_SESSION['username'] . "','action':'up-layer','ip_address':'" . $ipAddress . "','updated_player_id':'" . $playersId . "','changes':" . json_encode($newValues) . "}";
                            writeToLog($logContent);
                        } else {
                            echo "Lỗi: " . $conn->error;
                            echo "<div class='edit-container'>
                                <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                              </div>";
                        }
                    } else {
                        echo "Không có sự thay đổi để cập nhật.";
                        echo "<div class='edit-container'>
                            <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                          </div>";
                    }
                }
            } else {
                $_SESSION['error'] = "Không đủ thẩm quyền!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
        ?>

        <style>
            .notification {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 15px;
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                border-radius: 5px;
                opacity: 1;
                transition: opacity 1s ease-in-out;
            }

            /* CSS để căn giữa phần sửa thông báo */
            .edit-container {
                width: 50%;
                margin: auto;
                text-align: center;
            }

            .center-textarea {
                width: 100%;
                box-sizing: border-box;
                text-align: center;
                resize: both;
                /* Cho phép kéo rộng và cao của textarea */
                overflow: auto;
                /* Hiển thị thanh trượt nếu nội dung quá lớn */
            }

            .back-link {
                display: inline-block;
                margin-top: 10px;
                padding: 5px 10px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>
        <script>
            function scrollToBottom() {
                window.scrollTo(0, document.body.scrollHeight);
            }

            function scrollToTop() {
                window.scrollTo(0, 0);
            }
        </script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </div>
</div>

<?php include_once './end.php'; ?>

<?php
// Hàm ghi log
function writeToLog($content)
{
    $logFile = 'logs/admin_actions.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $content\n";

    // Tạo thư mục logs nếu chưa tồn tại
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }

    // Ghi log vào file
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
?>