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
$option = [];

if (!empty($option)) {
    $jsonOption = json_encode($option);
} else {
    $jsonOption = 'Không biết cái gì thì đừng thêm!!';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (checkAdmin($conn, $_SESSION['username'])) {
    $NameNV = $_POST["ninja_name"];
    $itemId = $_POST["id"];

    $checkOnlineQuery = "SELECT u.id, u.online FROM player u
                         JOIN ninja p ON u.id = p.id
                         WHERE p.name = '$NameNV'";
    $onlineResult = $conn->query($checkOnlineQuery);

    if ($onlineResult->num_rows > 0) {
        $onlineRow = $onlineResult->fetch_assoc();
        $userId = $onlineRow["id"];
        $userOnlineStatus = $onlineRow["online"];

        if ($userOnlineStatus == 1) {
            $_SESSION['error'] = "Tài khoản chưa thoát";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    } else {
        $_SESSION['error'] = "Không tìm thấy thằng loz $NameNV";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }

    $lock = $_POST["lock"] == "true" ? true : false;
    $yen = isset($_POST["yen"]) ? (int)$_POST["yen"] : 0;
    $quantity = $_POST["quantity"];
    $upgrade = isset($_POST["upgrade"]) ? (int)$_POST["upgrade"] : 0;
    $sys = isset($_POST["sys"]) ? (int)$_POST["sys"] : 0;
    $expire = -1;

    $userOption = isset($_POST["option"]) ? json_decode($_POST["option"]) : null;
    $option = !empty($userOption) ? $userOption : [];

    $sql = "SELECT * FROM ninja WHERE name = '$NameNV'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $bagData = json_decode($row["itemBag"], true);
        $numberCellBag = $row["levelBag"];
        $newIndex = 0;

        foreach ($bagData as $item) {
            if ($item["index"] === $newIndex) {
                $newIndex++;
            }

            if ($newIndex >= $numberCellBag) {
                $_SESSION['error'] = "Hành trang không đủ chỗ trống";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            }
        }

        $newItem = [
            "isLock" => $lock,
            "yen" => $yen,
            "quantity" => (int)$quantity,
            "upgrade" => $upgrade,
            "index" => $newIndex,
            "id" => (int)$itemId,
            "sys" => $sys,
            "expire" => $expire,
            "options" => $option
        ];

        $bagData[] = $newItem;

        $updatedbag = json_encode($bagData);
        $updateSql = "UPDATE ninja SET itemBag = '$updatedbag' WHERE name = '$NameNV'";
        $conn->query($updateSql);

        $queryItemName = "SELECT name FROM item WHERE id = $itemId";
        $resultItemName = $conn->query($queryItemName);

        if ($resultItemName->num_rows > 0) {
            $itemRowName = $resultItemName->fetch_assoc();
            $itemName = $itemRowName["name"];
        } else {
            $_SESSION['error'] = "Không tìm thấy item với ID $itemId cho thằng $NameNV";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }

        $_SESSION['success'] = "Đã thêm $quantity item $itemName cho thằng $NameNV";
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $playersName = $NameNV;
        $logSuccess = $playersName . " đã thêm $quantity item $itemName cho thằng $NameNV - Thời điểm: " . date("d F, Y, H:i:s") . PHP_EOL;
        file_put_contents('./lichsu/guido/send_success_' . date("j.n.Y") . '.log', $logSuccess, FILE_APPEND);
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        $_SESSION['error'] = "Không tìm thấy thằng $NameNV";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
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

        <form method="post" action="">
            Tên nhân vật <input type="text" name="ninja_name" required><br>
            idItem: <input type="text" name="id" required><br>
            Số lượng: <input type="number" name="quantity" required><br>
            Item Khoá ? 
            <select name="lock" required>
                <option value="true">Khoá</option>
                <option value="false">Không khoá</option>
            </select><br>
            Yên bán: <input type="number" name="yen" value="0"><br>
            Nâng cấp (0-16): <input type="number" name="upgrade" min="0" max="16" value="0"><br>
            Hệ: 
            <select name="sys" required>
                <option value="0">Không hệ</option>
                <option value="1">Hoả</option>
                <option value="2">Thuỷ</option>
                <option value="3">Phong</option>
            </select><br>
            
            Chỉ số: <input type="text" name="option" value="<?php echo htmlspecialchars($jsonOption); ?>"><br>

            <input type="submit" value="Gửi Đồ">
        </form>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            document.addEventListener('DOMContentLoaded', function () {
                var optionInput = document.querySelector('input[name="option"]');
                optionInput.addEventListener('blur', function () {
                    var optionValue = optionInput.value.trim();
                    if (optionValue !== '' && !/^\[.*\]$/.test(optionValue)) {
                        optionInput.value = '[' + optionValue + ']';
                    }
                });
            });
        </script>

    </div>
</div>
<style>
    form {
        max-width: 400px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        background-color: black;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px; /*khoảng cách các ô nhập*/
    }

    input[type="submit"]:hover {
        background-color: #f0ecea;
    }
</style>
<?php include_once './end.php'; ?>
