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
<?php

if (isset($_POST['delWebshop'])) {
    if (checkAdmin($conn, $_SESSION['username'])) {
        // Xóa item shop
        $idshop = mysqli_real_escape_string($conn, $_POST['delWebshop']);
        $result = $conn->query("SELECT * FROM webshop WHERE id = '$idshop'");
        $check = mysqli_fetch_array($result);

        $query = "DELETE FROM webshop WHERE id='$idshop' ";
        $query_run = $conn->query($query);
        if ($query_run) {
            $_SESSION['success'] = "Đã xóa item shop. " . $check['ten_item'];
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else {
            $_SESSION['error'] = "Không thể xóa item shop!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        }
    } else {
        $_SESSION['error'] = "Không đủ thẩm quyền!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }
}

if (isset($_POST['upwebshop'])) {
    if (checkAdmin($conn, $_SESSION['username'])) {
        // Thêm item shop
        $tenitem = mysqli_real_escape_string($conn, $_POST['tenitem']);
        $chisoweb = mysqli_real_escape_string($conn, $_POST['chisoweb']);
        $giaitem = mysqli_real_escape_string($conn, $_POST['giaitem']);
        $chisogame = mysqli_real_escape_string($conn, $_POST['chisogame']);
        $img_loc = $_FILES['link']['tmp_name'];
        $img_name = $_FILES['link']['name'];
        $img_des = "/uploadshop/" . $img_name;
        move_uploaded_file($img_loc, 'uploadshop/' . $img_name);

        if ($img_des == '') {
            $_SESSION['error'] = "Link file không được bỏ trống!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else if ($tenitem == '') {
            $_SESSION['error'] = "Tên item không được bỏ trống!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        }/* else if ($chisoweb == '') {
        $_SESSION['error'] = "Chi so item không được bỏ trống!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }*/ else if ($giaitem == '') {
            $_SESSION['error'] = "Giá coin không được bỏ trống!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else if ($chisogame == '') {
            $_SESSION['error'] = "Chi so game không được bỏ trống!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else {
            $query = "INSERT INTO `webshop`(`ten_item`, `chi_so_web`, `chi_so_game`,`gia_coin`, `image`) VALUES ('$tenitem','$chisoweb','$chisogame','$giaitem','$img_des')";
            $query_run = $conn->query($query);
            if ($query_run) {
                $_SESSION['success'] = "Thêm item $tenitem vào shop!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            } else {
                $_SESSION['success'] = "Có lỗi xảy ra vui lòng liên hệ admin để khắc phục!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
    } else {
        $_SESSION['error'] = "Không đủ thẩm quyền!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }
}

if (isset($_POST['editWebshop'])) {
    if (checkAdmin($conn, $_SESSION['username'])) {
        // Cập nhật chi so game
        $id = $_POST['id'];
        $chisogame = mysqli_real_escape_string($conn, $_POST['chisogame']);

        $query = "UPDATE `webshop` SET `chi_so_game`='$chisogame' WHERE `id`='$id'";
        $query_run = $conn->query($query);
        if ($query_run) {
            $_SESSION['success'] = "Cập nhật dữ liệu thành công!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else {
            $_SESSION['error'] = "Không thể cập nhật dữ liệu!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        }
    } else {
        $_SESSION['error'] = "Không đủ thẩm quyền!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }
}

if (isset($_POST['deleteWebshop'])) {
    if (checkAdmin($conn, $_SESSION['username'])) {
        // Xoá item shop
        $id = $_POST['id'];
        $result = $conn->query("SELECT * FROM webshop WHERE id = '$id'");
        $check = mysqli_fetch_array($result);

        $query = "DELETE FROM webshop WHERE id='$id' ";
        $query_run = $conn->query($query);
        if ($query_run) {
            $_SESSION['success'] = "Đã xóa item shop. " . $check['ten_item'];
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else {
            $_SESSION['error'] = "Không thể xóa item shop!";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        }
    } else {
        $_SESSION['error'] = "Không đủ thẩm quyền!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }
}

?>
<div class="card">
    <div class="card-body">
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>
        <div class="main" style="background: #ffffff00;">
            <div class="py-3 text-center">
                <h2>Upload Item Shop</h2>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
                <label for="">Tên Item:</label>
                <input class="form-control" type="text" name="tenitem" required><br>
                <!--<label for="">Chỉ số web:</label>
                <input class="form-control" type="text" name="chisoweb" required><br>-->
                <label for="">Giá Item:</label>
                <input class="form-control" type="text" name="giaitem" required><br>
                <label for="">Chỉ số game:</label>
                <input class="form-control" type="text" name="chisogame" required><br>
                <label for="">Đường dẫn:</label>
                <input class="form-control" type="file" name="link" id="fileInput" required><br>
                <img src="" id="previewImage" style="max-width: 300px; max-height: 200px; margin-top: 10px; display: none;">
                <button class="btn btn-primary" type="submit" name="upwebshop">Upload</button>
            </form>
        </div>

        <script>
            document.getElementById("fileInput").addEventListener("change", previewImage);

            function previewImage() {
                var preview = document.getElementById("previewImage");
                var fileInput = document.getElementById("fileInput");
                var file = fileInput.files[0];

                if (window.FileReader && file && file.type.startsWith("image/")) {
                    var reader = new FileReader();

                    reader.onloadend = function() {
                        preview.src = reader.result;
                        preview.style.display = "block";
                    }

                    reader.readAsDataURL(file);
                } else {
                    preview.src = "";
                    preview.style.display = "none";
                }
            }
        </script>
        <style>
            .item-container {
                border-radius: 10px;
                /* Điều chỉnh giá trị này để thay đổi độ cong của góc */
                overflow: hidden;
                margin-bottom: 10px;
            }

            .item {
                background: #fef7f8;
                border-bottom: 2px solid #8e8c8c8a;
                display: flex;
                justify-content: space-around;
                padding: 5px 0;
            }

            .item-img {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 60px;
                padding: 0 5px;
            }

            .item-img img {
                width: 80%;
            }

            .item-title {
                font-weight: 600;
                width: 130px;
            }

            .item-btn {
                display: flex;
                align-items: center;
            }

            .item-btn button {
                width: 60px;
                height: 25px;
                margin-left: 3px;
                margin-right: 2px;
                border-radius: 5px;
                background-color: #f0ecea;
                border: 0;
                padding: 2px;
            }

            .item-btn button:focus {
                border: 1px solid white;
            }

            .box-collap {
                background: #f5beb3;
                padding: 10px;
                border-bottom: 2px solid #8e8c8c8a;
                border-bottom-left-radius: 5px;
                border-bottom-right-radius: 5px;
            }

            .box-collap p {
                font-weight: 600;
                font-family: 'Courier';
            }

            .item-container .collapse {
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
            }

            .button1 {
                background-color: white;
                color: black;
                border: 2px solid black;
                border-radius: 8px;
                /* Điều chỉnh giá trị này để thay đổi độ cong của góc */
            }

            .button1:hover {
                background-color: black;
                color: white;
            }

            .shop-header {
                /* background-color: #db3646;*/
                padding: 10px;
                border-radius: 10px;
                /* Điều chỉnh giá trị này để thay đổi độ cong của góc */
                margin-bottom: 20px;
            }

            .shop-header h2 {
                color: black;
                margin: 0;
            }
        </style>
        <div class="main">
            <div class="box">
                <center class="shop-header">
                    <h2 style="color: white;">
                        <img src="/../images/candy.png" alt="Icon 1" style="height: 30px;">
                        WEB SHOP
                        <img src="/../images/candy-bag.png" alt="Icon 1" style="height: 30px;">
                    </h2>
                </center>
            </div>
            <?php
            $query = $conn->query("SELECT * FROM webshop");
            while ($row = mysqli_fetch_array($query)) {
            ?>
                <div class="item post-item d-flex align-items-center my-2">
                    <div class="item-img"><img src="<?= $row['image']; ?>" alt="" srcset=""></div>
                    <div class="item-title">
                        <p style="color: black; font-weight: bold;"># <?= $row['ten_item']; ?></p>
                        <p style="color: red; font-size: 14px; font-weight: 400;">Giá : <?= number_format($row['gia_coin']); ?> Coin</p>
                    </div>
                    <div class="item-btn my-2 my-md-0 mr-md-3">
                        <button class="btn btn-dangerxyz mb-3 px-2 py-1 fw-semibold border border-warning border" id="btn-view" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $row['id']; ?>" aria-expanded="false" aria-controls="collapseExample" style="color: black; display: flex; justify-content: center; align-items: center;">Xem</button>
                    </div>
                </div>
                <div class="collapse" id="collapseExample<?= $row['id']; ?>">
                    <div class="box-collap">
                        <p><span style="color: #fff;"><?= $row['ten_item']; ?></span></p>
                        <p><span style="color: #fff; font-size: 13px; font-weight: 400;">
                                <?php
                                $chi_so_game = json_decode($row['chi_so_game'], true);
                                if (isset($chi_so_game['isLock'])) {
                                    $isLocked = $chi_so_game['isLock'];
                                    echo '<p>';
                                    if ($isLocked) {
                                        echo '<span style="color: white; font-size: 13px; font-weight: 400;">Đã khoá</span>';
                                    } else {
                                        echo '<span style="color: white; font-size: 13px; font-weight: 400;">Không khoá</span>';
                                    }
                                    echo '</p>';
                                } else {
                                    echo '<p style="color: RED; font-size: 13px; font-weight: 400;">Dữ liệu không hợp lệ</p>';
                                }
                                ?>

                            </span></p>
                        <p><span style="color: yellow; font-size: 13px; font-weight: 400;">Giá bán: <?= number_format($row['gia_coin']); ?> Coin</span> </p>
                        <p><span style="color: RED; font-size: 13px; font-weight: 400;"><b style="font-style: italic;">Chỉ số: </b>
                                <?php
                                $chi_so_game = json_decode($row['chi_so_game'], true);
                                if (isset($chi_so_game['options']) && is_array($chi_so_game['options'])) {
                                    echo '<p style="color: #0096ff; font-size: 13px; font-weight: 400;">';
                                    foreach ($chi_so_game['options'] as $option) {
                                        $optionId = $option[0];
                                        $optionParam = $option[1];

                                        $queryitem_option = "SELECT `name` FROM `item_option` WHERE `id` = $optionId";
                                        $resultitem_option = $conn->query($queryitem_option);

                                        if ($resultitem_option && $resultitem_option->num_rows > 0) {
                                            $item_option = $resultitem_option->fetch_assoc();
                                            $filteredName = preg_replace('/[^\p{L}0-9:%\s\(\)+]+/u', '', $item_option['name']);

                                            echo $filteredName . ': ' . '+' . $optionParam . '<br>';
                                        } else {
                                            echo 'Dữ liệu không hợp lệ<br>';
                                        }
                                    }

                                    echo '</p>';
                                } else {
                                    echo '<p style="color: RED; font-size: 13px; font-weight: 400;">Dữ liệu không hợp lệ</p>';
                                }
                                ?>
                            </span></p>

                        <form action="" method="POST">
                            <div class="form-floating">
                                <textarea class="form-control" id="floatingTextarea2" name="chisogame" style="height: 100px"><?= $row['chi_so_game']; ?></textarea>
                                <label for="floatingTextarea2">Chỉ số game</label>
                            </div>
                            <div class="item-btn-coll">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                </br>
                                <button type="submit" name="editWebshop" class="btn btn-primary">Lưu</button>
                                <button type="submit" name="deleteWebshop" class="btn btn-danger">Xóa</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php include('end.php'); ?>