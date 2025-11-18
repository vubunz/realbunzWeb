<?php
ob_start();
include_once './main.php';

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
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th,
            td {
                padding: 12px;
                text-align: left;
                border: 1px solid #ddd;
            }

            th {
                background-color: #f2f2f2;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f5f5f5;
            }

            textarea {
                width: 100%;
                box-sizing: border-box;
                padding: 8px;
                margin: 4px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
                resize: vertical;
            }

            button {
                padding: 8px 12px;
                cursor: pointer;
            }

            button:hover {
                opacity: 0.8;
            }

            .edit-container {
                width: 80%;
                margin: auto;
                text-align: center;
                margin-top: 20px;
            }

            .notification {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                padding: 15px;
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                border-radius: 5px;
                opacity: 1;
                transition: opacity 1s ease-in-out;
            }
        </style>
        <?php
        // Kiểm tra kết nối cơ sở dữ liệu
        if ($conn->connect_error) {
            die("Kết nối không thành công: " . $conn->connect_error);
        }

        // Xử lý form sửa đổi nội dung
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["edit"])) {
                $news_portsId = $_POST["news_portsId"];

                // Lấy dữ liệu hiện tại từ database
                $sqlSelectCurrentData = "SELECT * FROM news_posts WHERE id = $news_portsId";
                $resultCurrentData = $conn->query($sqlSelectCurrentData);

                if ($resultCurrentData->num_rows > 0) {
                    $rowCurrentData = $resultCurrentData->fetch_assoc();

                    // Tạo mảng chứa dữ liệu cần cập nhật
                    $updateData = array();
                    foreach ($_POST as $key => $value) {
                        // Loại bỏ các trường không cần thiết
                        if ($key != "edit" && $key != "news_portsId") {
                            // So sánh giá trị hiện tại với giá trị mới
                            if ($rowCurrentData[$key] != $value) {
                                $updateData[$key] = $value;
                            }
                        }
                    }

                    // Thêm cột updated_at vào mảng $updateData
                    $updateData['updated_at'] = date("Y-m-d H:i:s");

                    // Nếu có dữ liệu cần cập nhật
                    if (!empty($updateData)) {
                        // Xây dựng câu lệnh SQL để cập nhật dữ liệu
                        $sqlUpdate = "UPDATE news_posts SET ";
                        foreach ($updateData as $key => $value) {
                            $sqlUpdate .= "$key = '$value', ";
                        }
                        // Loại bỏ dấu ',' cuối cùng
                        $sqlUpdate = rtrim($sqlUpdate, ', ');
                        $sqlUpdate .= " WHERE id = $news_portsId";

                        if ($conn->query($sqlUpdate) === TRUE) {
                            // Hiển thị bài viết thành công
                            echo "<script>
                                var notification = document.createElement('div');
                                notification.innerHTML = 'Cập nhật bài viết thành công';
                                notification.classList.add('notification');
                                document.body.appendChild(notification);

                                setTimeout(function () {
                                    notification.style.opacity = '0';
                                    setTimeout(function () {
                                        document.body.removeChild(notification);
                                    }, 1000);
                                }, 2345);
                            </script>";
                        } else {
                            echo "Lỗi: " . $conn->error;
                        }
                    } else {
                        // Hiển thị bài viết nếu không có dữ liệu cần cập nhật
                        echo "<script>
                            var notification = document.createElement('div');
                            notification.innerHTML = 'Không có dữ liệu cần cập nhật';
                            notification.classList.add('notification');
                            document.body.appendChild(notification);

                            setTimeout(function () {
                                notification.style.opacity = '0';
                                setTimeout(function () {
                                    document.body.removeChild(notification);
                                }, 1000);
                            }, 2345);
                        </script>";
                    }
                }
            } elseif (isset($_POST["delete"])) {
                $news_portsId = $_POST["news_portsId"];

                // Xây dựng câu lệnh SQL để xóa bài viết
                $sqlDelete = "DELETE FROM news_posts WHERE id = $news_portsId";

                if ($conn->query($sqlDelete) === TRUE) {
                    // Hiển thị thông báo xóa thành công
                    echo "<script>
                        var notification = document.createElement('div');
                        notification.innerHTML = 'Xóa bài viết thành công';
                        notification.classList.add('notification');
                        document.body.appendChild(notification);

                        setTimeout(function () {
                            notification.style.opacity = '0';
                            setTimeout(function () {
                                document.body.removeChild(notification);
                            }, 1000);
                        }, 2345);
                    </script>";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            } elseif (isset($_POST["add"])) {
                // Xử lý thêm mới bài viết
                $slug = $_POST["slug"];
                $title = $_POST["title"];
                $short_content = $_POST["short_content"];
                $content = $_POST["content"];
                $views = $_POST["views"];

                // Thêm cột updated_at vào mảng $updateData
                $updated_at = date("Y-m-d H:i:s");

                // Xây dựng câu lệnh SQL để thêm mới bài viết
                $sqlAdd = "INSERT INTO huong_dan (slug, title, short_content, content, views, updated_at) 
                           VALUES ('$slug', '$title', '$short_content', '$content', '$views', '$updated_at')";

                if ($conn->query($sqlAdd) === TRUE) {
                    // Hiển thị thông báo thêm mới thành công
                    echo "<script>
                        var notification = document.createElement('div');
                        notification.innerHTML = 'Thêm mới bài viết thành công';
                        notification.classList.add('notification');
                        document.body.appendChild(notification);

                        setTimeout(function () {
                            notification.style.opacity = '0';
                            setTimeout(function () {
                                document.body.removeChild(notification);
                            }, 1000);
                        }, 2345);
                    </script>";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            }
        }
        ?>
        <?php
        /* // Kiểm tra kết nối cơ sở dữ liệu
        if ($conn->connect_error) {
            die("Kết nối không thành công: " . $conn->connect_error);
        }

        // Xử lý form sửa đổi nội dung
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["edit"])) {
                $huong_danId = $_POST["huong_danId"];

                // Lấy dữ liệu hiện tại từ database
                $sqlSelectCurrentData = "SELECT * FROM huong_dan WHERE id = $huong_danId";
                $resultCurrentData = $conn->query($sqlSelectCurrentData);

                if ($resultCurrentData->num_rows > 0) {
                    $rowCurrentData = $resultCurrentData->fetch_assoc();

                    // Tạo mảng chứa dữ liệu cần cập nhật
                    $updateData = array();
                    foreach ($_POST as $key => $value) {
                        // Loại bỏ các trường không cần thiết
                        if ($key != "edit" && $key != "huong_danId") {
                            // So sánh giá trị hiện tại với giá trị mới
                            if ($rowCurrentData[$key] != $value) {
                                $updateData[$key] = $value;
                            }
                        }
                    }

                    // Nếu có dữ liệu cần cập nhật
                    if (!empty($updateData)) {
                        // Xây dựng câu lệnh SQL để cập nhật dữ liệu
                        $sqlUpdate = "UPDATE huong_dan SET ";
                        foreach ($updateData as $key => $value) {
                            $sqlUpdate .= "$key = '$value', ";
                        }
                        // Loại bỏ dấu ',' cuối cùng
                        $sqlUpdate = rtrim($sqlUpdate, ', ');
                        $sqlUpdate .= " WHERE id = $huong_danId";

                        if ($conn->query($sqlUpdate) === TRUE) {
                            // Hiển thị bài viết thành công
                            echo "<script>
                                var notification = document.createElement('div');
                                notification.innerHTML = 'Cập nhật bài viết thành công';
                                notification.classList.add('notification');
                                document.body.appendChild(notification);

                                setTimeout(function () {
                                    notification.style.opacity = '0';
                                    setTimeout(function () {
                                        document.body.removeChild(notification);
                                    }, 1000);
                                }, 2345);
                            </script>";
                        } else {
                            echo "Lỗi: " . $conn->error;
                        }
                    } else {
                        // Hiển thị bài viết nếu không có dữ liệu cần cập nhật
                        echo "<script>
                            var notification = document.createElement('div');
                            notification.innerHTML = 'Không có dữ liệu cần cập nhật';
                            notification.classList.add('notification');
                            document.body.appendChild(notification);

                            setTimeout(function () {
                                notification.style.opacity = '0';
                                setTimeout(function () {
                                    document.body.removeChild(notification);
                                }, 1000);
                            }, 2345);
                        </script>";
                    }
                }
            } elseif (isset($_POST["delete"])) {
                $huong_danId = $_POST["huong_danId"];

                // Xây dựng câu lệnh SQL để xóa bài viết
                $sqlDelete = "DELETE FROM huong_dan WHERE id = $huong_danId";

                if ($conn->query($sqlDelete) === TRUE) {
                    // Hiển thị thông báo xóa thành công
                    echo "<script>
                        var notification = document.createElement('div');
                        notification.innerHTML = 'Xóa bài viết thành công';
                        notification.classList.add('notification');
                        document.body.appendChild(notification);

                        setTimeout(function () {
                            notification.style.opacity = '0';
                            setTimeout(function () {
                                document.body.removeChild(notification);
                            }, 1000);
                        }, 2345);
                    </script>";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            } elseif (isset($_POST["add"])) {
                // Xử lý thêm mới bài viết
                $slug = $_POST["slug"];
                $title = $_POST["title"];
                $short_content = $_POST["short_content"];
                $content = $_POST["content"];
                $views = $_POST["views"];

                // Xây dựng câu lệnh SQL để thêm mới bài viết
                $sqlAdd = "INSERT INTO huong_dan (slug, title, short_content, content, views) 
                           VALUES ('$slug', '$title', '$short_content', '$content', '$views')";

                if ($conn->query($sqlAdd) === TRUE) {
                    // Hiển thị thông báo thêm mới thành công
                    echo "<script>
                        var notification = document.createElement('div');
                        notification.innerHTML = 'Thêm mới bài viết thành công';
                        notification.classList.add('notification');
                        document.body.appendChild(notification);

                        setTimeout(function () {
                            notification.style.opacity = '0';
                            setTimeout(function () {
                                document.body.removeChild(notification);
                            }, 1000);
                        }, 2345);
                    </script>";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            }
        }*/
        ?>

        <?php
        // Truy vấn dữ liệu từ bảng huong_dan
        $sqlSelect = "SELECT * FROM news_posts";
        $result = $conn->query($sqlSelect);

        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Link bài</th>
                        <th>Tiêu đề</th>
                        <th>Tóm gọn nội dung</th>
                        <th>Nội dung</th>
                        <th>Views</th>
                        <th>Lưu</th>
                        <th>Xóa</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";

                // Duyệt qua các cột (ID, Slug, Title, Short Content, Content, Views)
                $columns = array("id", "slug", "title", "short_content", "content", "views");
                foreach ($columns as $column) {
                    echo "<td>";
                    echo "<form method='post' action=''>
                            <input type='hidden' name='news_portsId' value='" . $row["id"] . "'>";

                    // Sử dụng textarea cho tất cả các cột
                    echo "<textarea class='center-textarea' name='$column' placeholder='Nội dung mới' required>" . $row[$column] . "</textarea>";

                    echo "</td>";
                }

                // Nút chỉnh sửa
                echo "<td>
                        <button type='submit' name='edit' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer'>Chỉnh sửa</button>
                    </form>
                </td>";

                // Nút xóa
                echo "<td>
                        <form method='post' action=''>
                            <input type='hidden' name='news_portsId' value='" . $row["id"] . "'>
                            <button type='submit' name='delete' class='mb-3 px-2 py-1 fw-semibold text-secondary bg-danger bg-opacity-25 border border-danger border-opacity-75 rounded-2 link-success cursor-pointer'>Xóa</button>
                        </form>";

                echo "</td>";

                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<div class='edit-container'>Không có Bài viết nào</div>";
        }
        ?>
        </br>
        <style>
            .add-post-container {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
                width: 100%;

                box-sizing: border-box;
                margin: 0 auto;
                /* Thêm dòng này để căn giữa theo chiều ngang */
            }

            h2 {
                text-align: center;
                color: #333;
            }

            form {
                display: flex;
                flex-direction: column;
            }

            label {
                margin-top: 10px;
                color: #555;
            }

            input,
            textarea,
            button {
                margin-top: 5px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
                width: 100%;
            }

            textarea {
                resize: vertical;
            }

            button {
                background-color: #4caf50;
                color: #fff;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #45a049;
            }

            button {
                background-color: #000;
                /* Màu đen */
                color: #fff;
                /* Màu chữ trắng */
                cursor: pointer;
                transition: background-color 0.3s;
            }

            button:hover {
                background-color: #333;
                /* Màu đen nhạt khi hover */
            }

            textarea {
                width: 100%;
                height: 100%;
                /* Thiết lập chiều cao là 100% để textarea có thể kéo rộng ra cả ngoài trang web */
                resize: both;
                /* Cho phép kéo rộng và kéo cao */
                padding: 8px;
                margin: 4px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
        </style>




        <div class="add-post-container">
            <h2>Thêm bài viết (Danh mục)</h2>
            <form method="post" action="">
                <input type="hidden" name="add">
                <label for="slug">Link bài:</label>
                <input type="text" name="slug" required>
                <label for="title">Tiêu đề:</label>
                <input type="text" name="title" required>
                <label for="short_content">Tóm gọn nội dung:</label>
                <textarea name="short_content" required></textarea>
                <label for="content">Nội dung:</label>
                <textarea name="content" required></textarea>
                <label for="views">Views:</label>
                <input type="number" name="views" value="0" required>
                <button type="submit">Thêm mới</button>
            </form>
        </div>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </div>
</div>
<?php include_once './end.php'; ?>