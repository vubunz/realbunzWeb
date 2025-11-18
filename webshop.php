
<?php include_once './main.php'; ?>
<?php
if (!isset($_SESSION['username'])) {
    header('Location: /');
}
if (!$connect) {
    exit(0);
}

if (isset($_POST['buyWebshop'])) {
    $itemid = $_POST['buyWebshop'];
    $result = $conn->query("SELECT * FROM webshop WHERE id = '$itemid'");

    if (!$result) {
        echo "Error executing query: " . $conn->error;
        exit(0);
    }

    $row = $result->fetch_assoc();

    if (!$row) {
        echo "No data found for item with ID: $itemid";
        exit(0);
    }

    $username = $_SESSION['username'];

    $sql = "SELECT * FROM `users` WHERE `username` = '$username'";
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        echo "Error executing query: " . mysqli_error($conn);
        exit(0);
    }

    $num_rows = mysqli_num_rows($query);

    if ($num_rows == 0) {
        $_SESSION['error'] = "Tài khoản cần tạo nhân vật để sử dụng chức năng này! ";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    }

    $row_users = mysqli_fetch_assoc($query);
    $idnhanvat = $row_users["id"];

    // Truy vấn thông tin người chơi từ bảng players dựa trên user_id
    $result_player = $conn->query("SELECT name FROM ninja WHERE id = '$idnhanvat'");
    
    if (!$result_player) {
        echo "Error executing player query: " . $conn->error;
        exit(0);
    }
    
    $row_player = $result_player->fetch_assoc();
    
    if (!$row_player) {
        exit(0);
    }
    
    $nhanvat = $row_player["name"];
    

    $result1 = $conn->query("SELECT * FROM ninja WHERE name = '$nhanvat'");
    $players = $result1->fetch_assoc();

    if (!$players || $players['name'] == 0) {
        $_SESSION['error'] = "Tài khoản cần tạo nhân vật để sử dụng chức năng này! ";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    } elseif ($row_users['online'] != 0) {
        $_SESSION['error'] = "Tài khoản chưa thoát!";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit(0);
    } else {
        $giacoin = $row['gia_coin'];
        //$coin = $players['coin'];
        $coin = $row_users['coin'];
        if ($coin < $giacoin) {
            $_SESSION['error'] = "Số coin không đủ!!! Còn thiếu: " . number_format($giacoin - $coin) . " Coin. <a href='/recharge' style='color: blue;'>Bơm lúa ngay</a>";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit(0);
        } else {
            $bag = $players['itemBag'];
            $bag = json_decode($bag, true);

            if ($players['levelBag'] < count($bag)) {
                $_SESSION['error'] = "Hành trang không đủ chỗ trống.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            } else {
                for ($i = 0; $i < count($bag); $i++) {
                    $bag[$i]["index"] = $i;
                }

                $webitem = $row['chi_so_game'];
                $temp = json_decode($webitem, true);

                $temp["index"] = count($bag);
                $bag[] = $temp;

                $players['itemBag'] = json_encode($bag);

                $itembuy = $players['itemBag'];

                $updateCoinQuery = "UPDATE player SET coin = " . ($coin - $giacoin) . " WHERE id = '" . $row_users["id"] . "'";
                //$updateCoinQuery = "UPDATE users SET coin = " . ($coin - $giacoin) . " WHERE name = '" . $nhanvat . "'";
                $up = $conn->query($updateCoinQuery);

                $updatebagQuery = "UPDATE ninja SET itemBag = '" . $itembuy . "' WHERE name = '" . $nhanvat . "'";
                $up1 = $conn->query($updatebagQuery);

                //$_SESSION['success'] = "Đã mua item: " . $row['ten_item'] . " cho nhân vật: " . $nhanvat . ". Bạn đã sở hữu: " . $itembuy;
                $_SESSION['success'] = "Đã mua item: " . $row['ten_item'];
                date_default_timezone_set("Asia/Ho_Chi_Minh");
                $playersName = $nhanvat;
                $logSuccess = $playersName . " đã mua item: " . $row['ten_item'] . " - Giá: " . $row['gia_coin'] . " Coin - Coin trước mua: " . $coin . " - Coin sau mua: " . ($coin - $giacoin) . " - Thời điểm: " . date("d F, Y, H:i:s") . PHP_EOL;

                file_put_contents('./lichsu/muado/buy_success_' . date("j.n.Y") . '.log', $logSuccess, FILE_APPEND);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
    }
}
?>

<div class="card">
    <div class="card-body">
<?php include('success.php'); ?>
<?php include('error.php'); ?>


<center>
    <h2 style="color: #fe7d90;">
    🍒<!--<img src="/../images/candy.png" alt="Icon 1" style="height: 30px;">--> WEB SHOP
    🍒<!--<img src="/../images/candy-bag.png" alt="Icon 1" style="height: 30px;">-->
    </h2>
</center>

<div class="item-container">
    <?php
    $query = $conn->query("SELECT * FROM webshop");
    while ($row = $query->fetch_assoc()) { ?>
        <div class="item">
            <div class="item-img"><img src="<?= $row['image']; ?>" alt="" srcset=""></div>
            <div class="item-title">
                <p style="color: red;"># <?= $row['ten_item']; ?></p>
                <p style="color: #00c0ff; font-size: 14px; font-weight: 400;">Giá : <?= number_format($row['gia_coin']); ?> Coin</p>
            </div>
            <div class="item-btn">
                <!--<button class="button button1" id="btn-view" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $row['id']; ?>" aria-expanded="false" aria-controls="collapseExample">Xem Sẽ</button>-->
                <button class="ms-1 mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer" id="btn-view" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $row['id']; ?>" aria-expanded="false" aria-controls="collapseExample" style="color: black;">Xem</button>
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
                <p><span style="color: RED; font-size: 13px; font-weight: bold;"><?= 'Giá bán: ' . number_format($row['gia_coin']) . ' Coin'; ?></span></p>
                <p><span style="color: #0096ff; font-size: 13px; font-weight: 400;"><b style="font-style: italic;">Chỉ số: </b>
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
                <div class="item-btn-coll">
                    <form action="" method="POST">
                        <button type="submit" name="buyWebshop" value="<?= $row['id']; ?>" class="btn btn-primary px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer">MUA</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="khoangcach"></div>
    <?php } ?>

</div>
</div>
</div>
<link rel="stylesheet" type="text/css" href="style.css">
<?php include('end.php'); ?>