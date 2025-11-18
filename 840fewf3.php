<?php
ob_start();
include_once './main.php';

function writeToLog($content)
{
    $logFile = './lichsu/bufftk/bufftk_logs_' . date("j.n.Y") . '.log';
    $log = "Host: " . $_SERVER['REMOTE_ADDR'] . " - " . date("F j, Y, g:i a") . PHP_EOL .
        "Content: " . $content . PHP_EOL .
        "-------------------------" . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND);
}

if (!isset($_SESSION['username'])) {
    header('Location: /');
}
if (!checkAdmin($conn, $_SESSION['username'])) {
    header('Location: /');
}



ob_end_flush();
?>

<div class="card">
    <div class="card-body">
        <center>
            <h2 style="color: black;">Buff tài khoản</h2>
        </center>
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>
        <div class='edit-container'>
            <form method='post' action='' novalidate>
                <input type='text' name='searchName' placeholder='Nhập tên để tìm kiếm'>
                <button type='submit' name='search' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Tìm kiếm</button>
            </form>
        </div>

        <?php
        if ($conn->connect_error) {
            die("Kết nối không thành công: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (checkAdmin($conn, $_SESSION['username'])) {
                if (isset($_POST["search"])) {
                    $searchName = $conn->real_escape_string($_POST["searchName"]);
                    $sqlSelect = "SELECT * FROM users WHERE username = '$searchName'";
                    $result = $conn->query($sqlSelect);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='edit-container'>
                                <button onclick='scrollToBottom()' class='scroll-button mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Xuống cuối trang</button>
                              </div><br>";

                            echo "<div class='edit-container'>
                                <form method='post' action=''>
                                    <input type='hidden' name='playersId' value='" . $row["id"] . "'>";

                            foreach ($row as $columnName => $columnValue) {
                                echo "<div class='form-group'>
                                    <label for='$columnName'>$columnName:</label>";
                                echo "<textarea class='form-control center-textarea' name='$columnName'>$columnValue</textarea>";
                                echo "</div>";
                            }

                            echo "<br><button type='submit' name='edit' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Chỉnh sửa</button>
                                </form>
                            </div>";

                            echo "<br><div class='edit-container'>
                                <button onclick='scrollToTop()' class='scroll-button mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Lên đầu trang</button>
                              </div>";
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
                    $sqlSelectOld = "SELECT * FROM player WHERE id = $playersId";
                    $resultOld = $conn->query($sqlSelectOld);
                    $oldValues = array();

                    if ($resultOld->num_rows > 0) {
                        $rowOld = $resultOld->fetch_assoc();
                        $oldValues = $rowOld;
                    }

                    $newValues = array();
                    foreach ($_POST as $key => $value) {
                        if ($key != "ninjaId" && $key != "edit") {
                            if ($oldValues[$key] != $value) {
                                $newValues[$key] = $conn->real_escape_string($value);
                            }
                        }
                    }

                    if (!empty($newValues)) {
                        if (isset($newValues['username'])) {
                            $newName = $newValues['username'];
                            $checkDuplicateName = "SELECT id FROM player WHERE username = '$newName' AND id != $playersId";
                            $resultDuplicateName = $conn->query($checkDuplicateName);

                            if ($resultDuplicateName->num_rows > 0) {
                                echo "Lỗi: Tên '$newName' đã tồn tại trong cơ sở dữ liệu.";
                                echo "<div class='edit-container'>
                                    <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                                  </div>";
                                exit;
                            }
                        }

                        if (isset($newValues['id'])) {
                            $newId = $newValues['id'];
                            $checkDuplicateId = "SELECT id FROM player WHERE id = $newId AND id != $playersId";
                            $resultDuplicateId = $conn->query($checkDuplicateId);

                            if ($resultDuplicateId->num_rows > 0) {
                                echo "Lỗi: ID '$newId' đã tồn tại trong cơ sở dữ liệu.";
                                echo "<div class='edit-container'>
                                    <a href='javascript:history.back()' class='back-link'>Quay lại</a>
                                  </div>";
                                exit;
                            }
                        }

                        $setClause = "";
                        $changes = array(); // Mảng để lưu trữ các thay đổi
                        foreach ($newValues as $columnName => $columnValue) {
                            $setClause .= "$columnName = '$columnValue', ";
                            $changes[$columnName] = array(
                                'old' => $oldValues[$columnName],
                                'new' => $columnValue
                            );
                        }
                        $setClause = rtrim($setClause, ', ');

                        $sqlUpdate = "UPDATE users SET $setClause WHERE id = $playersId";

                        if ($conn->query($sqlUpdate) === TRUE) {
                            $ipAddress = $_SERVER['REMOTE_ADDR'];

                            // Gọi hàm writeToLog khi cập nhật thành công với thông tin chi tiết thay đổi
                            $logContent = "{'username':'" . $_SESSION['username'] . "','action':'update_user','ip_address':'" . $ipAddress . "','changes':" . json_encode($changes) . "}";
                            writeToLog($logContent);

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
                overflow: auto;
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