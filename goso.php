<?php
ob_start();

include_once './main.php';
include_once './f3269rfkv.php';

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng về trang chủ
if (!isset($_SESSION['username']) || !checkAdmin($conn, $_SESSION['username'])) {
    header('Location: /');
    exit();
}
ob_end_flush();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = intval($_POST["item_id"]);
    $sys = isset($_POST["sys"]) ? intval($_POST["sys"]) : 0;
    $store = 14; // luôn là 14
    $lock = isset($_POST["lock"]) ? intval($_POST["lock"]) : 0;
    $coin = isset($_POST["coin"]) ? intval($_POST["coin"]) : 0;
    $gold = isset($_POST["gold"]) ? intval($_POST["gold"]) : 0;
    $yen = isset($_POST["yen"]) ? intval($_POST["yen"]) : 0;
    $expire = isset($_POST["expire"]) ? intval($_POST["expire"]) : -1;

    // Đổi tên bảng dưới đây thành tên bảng item của bạn
    $sql = "INSERT INTO store_data (item_id, sys, store, `lock`, coin, gold, yen, expire) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiii", $item_id, $sys, $store, $lock, $coin, $gold, $yen, $expire);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Thêm item thành công!</div>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<style>
    h5 {
        color: #0094fb;
        /* Màu chữ xanh */
    }

    button {
        background-color: #007BFF;
        /* Màu nền xanh đậm */
        color: white;
        /* Màu chữ trắng */
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        height: 30px;
        /* Chiều cao mong muốn */
        /*   line-height: 40px; /* Căn giữa nút và chữ */
        /*  vertical-align: middle; /* Căn giữa nút và chữ */
    }

    button:hover {
        background-color: #0056b3;
        /* Màu nền xanh nhạt khi hover */
    }
</style>
<div class="card">
    <div class="card-body">
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (checkAdmin($conn, $_SESSION['username'])) {
                // Xử lý khi có yêu cầu POST
                $code = $_POST["code"];
                $id = empty($_POST["item_id"]) ? 0 : $_POST["item_id"];
                $quantity = empty($_POST["item_quantity"]) ? 0 : $_POST["item_quantity"];
                $isLock = empty($_POST["item_isLock"]) ? 0 : $_POST["item_isLock"];
                $expires = empty($_POST["item_expires"]) ? 0 : $_POST["item_expires"];

                // Kiểm tra trùng lặp 'code' trước khi chèn
                $checkDuplicateSql = "SELECT COUNT(*) FROM gift_code WHERE code = ?";
                $checkDuplicateStmt = $conn->prepare($checkDuplicateSql);
                $checkDuplicateStmt->code("s", $code);
                $checkDuplicateStmt->item_id();
                $checkDuplicateStmt->item_quantity();
                $checkDuplicateStmt->item_isLock($count);
                $checkDuplicateStmt->item_expires();
                $checkDuplicateStmt->fetch();
                $checkDuplicateStmt->close();

                if ($count > 0) {
                    $_SESSION['error'] = "Thất bại! giftcode đã tồn tại!";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit(0);
                } else {
                    // Nếu không có dữ liệu nhập từ người dùng, thiết lập giá trị mặc định là []
                    $itemsArray = [];

                    // Nếu có dữ liệu nhập từ người dùng, xử lý và tạo mảng itemsArray
                    if (!empty($_POST["id"])) {
                        for ($i = 0; $i < count($_POST["id"]); $i++) {
                            // Xử lý items
                            $id = intval($_POST["id"][$i]);
                            $code = intval($_POST["code"][$i]);
                            $quantity = intval($_POST["item_quantity"][$i]);
                            $expires = $_POST["item_expires"][$i];
                            $isLock = $_POST["1"][$i];

                            // Kiểm tra xem options có phải là một chuỗi JSON hợp lệ không
                            try {
                                json_decode($options);
                            } catch (Exception $e) {
                                $options = '[]'; // Nếu không hợp lệ, gán giá trị mặc định
                            }

                            // Thêm trường 'isLock' với giá trị từ người dùng vào mỗi mục
                            $newItem = array(
                                "id" => $i,
                                "code" => $code,
                                "item_id" => $id,
                                "item_quantity" => $quantity,
                                "item_isLock" => ($isLock === '-1')  // Chuyển đổi chuỗi 'true' thành true, 'false' thành false
                            );

                            $itemsArray[] = $newItem;
                        }
                    }

                    // Chuyển đổi mảng items thành chuỗi JSON
                    $itemsJson = json_encode($itemsArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                    // Sử dụng câu lệnh prepared statement để chống SQL injection
                    $insertSql = "INSERT INTO gift_code (code, item_id, item_quantity, item_isLock, item_expires, isPlayer, player, time) VALUES (?, ?, ?, ?, ?, 0, 0, 0)";
                    $insertStmt = $conn->prepare($insertSql);
                    $insertStmt->bind_param("sssss", $code, $quantity, $isLock, $expires);

                    if ($insertStmt->execute()) {
                        $_SESSION['success'] = "Thêm giftcode thành công.";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                    } else {
                        $_SESSION['error'] = "ERROR! :((";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                    }

                    $insertStmt->close();
                }

                $conn->close();
            } else {
                $_SESSION['error'] = "Bạn không đủ thẩm quyền!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            }
        }
        ?>

        <div class="card1">
            <div class="card-body1">
                <h2 class="text-center" style="color: #0096ff; font-weight: bold;">Thêm Item Goso</h2>
                <!--start đổi ngày-->

                <h1>Timestamp Converter</h1>

                <form id="dateToTimestampForm">
                    <label for="inputDate">Input a specific date:</label>
                    <input type="date" id="inputDate" name="inputDate" required>
                    <button type="button" onclick="convertDateToTimestamp()">Convert to Timestamp</button>
                </form>
                <br>
                <form id="timestampToDateForm">
                    <label for="inputTimestamp">Input a timestamp:</label>
                    <input type="text" id="inputTimestamp" name="inputTimestamp" required>
                    <button type="button" onclick="convertTimestampToDate()">Convert to Date</button>
                </form>

                <div id="conversionResult"></div>

                <script>
                    function convertDateToTimestamp() {
                        var date = document.getElementById('inputDate').value;
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'conversion.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                document.getElementById('conversionResult').innerHTML = xhr.responseText;
                            }
                        };
                        xhr.send('action=dateToTimestamp&date=' + date);
                    }

                    function convertTimestampToDate() {
                        var timestamp = document.getElementById('inputTimestamp').value;
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'conversion.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                document.getElementById('conversionResult').innerHTML = xhr.responseText;
                            }
                        };
                        xhr.send('action=timestampToDate&timestamp=' + timestamp);
                    }
                </script>
                <br>
                <!--ending đổi ngày-->
                <form method="post">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Item ID:</label>
                        <div class="col-sm-10">
                            <input type="number" name="item_id" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Hệ (sys):</label>
                        <div class="col-sm-10">
                            <input type="number" name="sys" value="0" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Khóa:</label>
                        <div class="col-sm-10">
                            <select name="lock" class="form-control">
                                <option value="0">Khóa</option>
                                <option value="1">Không khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Coin (xu):</label>
                        <div class="col-sm-10">
                            <input type="number" name="coin" value="0" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Gold (lượng):</label>
                        <div class="col-sm-10">
                            <input type="number" name="gold" value="0" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Yên:</label>
                        <div class="col-sm-10">
                            <input type="number" name="yen" value="0" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Hạn sử dụng (expire):</label>
                        <div class="col-sm-10">
                            <input type="number" name="expire" value="-1" class="form-control">
                            <small>-1 là vĩnh viễn</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <input type="submit" value="Thêm Item" class="btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="activeThanhCong6" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" onclick="closeActiveThanhCong6()">&times;</span>
            <div class="modal-body text-center">
                <a href="/"><img class="logo" alt="Logo" src="/images/logo.png" style="max-width: 250px;"></a>
                <h2 style="color: #4285F4;">Thành Công</h2>
                <p id="activeThanhCongContent6"></p>
                <button class="modal-close-btn" onclick="closeActiveThanhCong6()">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="activeThatBai6" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" onclick="closeActiveThatBai6()">&times;</span>
            <div class="modal-body text-center">
                <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 250px;"></a>
                <h2 style="color: #E83F33;">Thất Bại</h2>
                <p id="activeThatBaiContent6"></p>
                <button class="modal-close-btn" onclick="closeActiveThatBai6()">OK</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS cho modal */
    .modal {
        /* ... */
        transition: opacity 0.3s ease-in-out;
        /* Thêm transition cho modal */
    }

    .modal.show {
        opacity: 1;
        /* Hiển thị modal mượt mà */
    }

    /* CSS cho nút OK */
    .modal-close-btn {
        background-color: #007BFF;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        transition: background-color 0.3s ease-in-out;
        /* Thêm transition cho nút OK */
    }

    /* Khi hover vào nút đóng */
    .modal-close-btn:hover {
        background-color: #0056b3;
    }
</style>

<script>
    function openActiveThanhCong6() {
        var activeThanhCong6 = document.getElementById('activeThanhCong6');
        activeThanhCong6.style.display = 'block';
    }

    function closeActiveThanhCong6() {
        var activeThanhCong6 = document.getElementById('activeThanhCong6');
        activeThanhCong6.style.display = 'none';
    }

    function openActiveThatBai6() {
        var activeThatBai6 = document.getElementById('activeThatBai6');
        activeThatBai6.style.display = 'block';
    }

    function closeActiveThatBai6() {
        var activeThatBai6 = document.getElementById('activeThatBai6');
        activeThatBai6.style.display = 'none';
    }

    // Mảng để lưu trữ dữ liệu items
    var itemsArray = [];

    // Hàm thêm một mục vào mảng itemsArray
    function addItem() {
        var itemDiv = $("<div class='form-group row'></div>");

        // Trường nhập liệu cho id vật phẩm
        var idInput = $("<input type='text' name='id[]' placeholder='Item ID' required class='form-control'>");
        itemDiv.append("<label for='item_id' class='col-sm-2 col-form-label' style='color: red; font-weight: bold;'>ID vật phẩm:</label>", "<div class='col-sm-10'>", idInput, "</div>");

        // Trường nhập liệu cho hạn sử dụng
        var expireInput = $("<input type='text' name='expire[]' placeholder='Expiration' value='-1' class='form-control'>");
        itemDiv.append("<label class='col-sm-2 col-form-label'>Hạn sử dụng:</label>", "<div class='col-sm-10'>", expireInput, "</div>");

        // Trường nhập liệu cho sys (hệ)
        var sysInput = $("<input type='text' name='sys[]' placeholder='Sys' value='0' class='form-control'>");
        itemDiv.append("<label class='col-sm-2 col-form-label'>Hệ:</label>", "<div class='col-sm-10'>", sysInput, "</div>");

        // Trường nhập liệu cho số lượng
        var quantityInput = $("<input type='text' name='quantity[]' placeholder='Quantity' value='1' class='form-control'>");
        itemDiv.append("<label class='col-sm-2 col-form-label'>Số lượng:</label>", "<div class='col-sm-10'>", quantityInput, "</div>");

        // Trường nhập liệu cho option
        var optionsInput = $("<input type='text' name='options[]' placeholder='Options (JSON)' value='[]' class='form-control'>");
        itemDiv.append("<label class='col-sm-2 col-form-label'>Options (JSON):</label>", "<div class='col-sm-10'>", optionsInput, "</div>");

        // Trường nhập liệu cho isLock
        var isLockInput = $("<select name='isLock[]' class='form-control'><option value='true'>True</option><option value='false'>False</option></select>");
        itemDiv.append("<label class='col-sm-2 col-form-label'>Khoá?:</label>", "<div class='col-sm-10'>", isLockInput, "</div>");

        // Thêm div chứa các trường nhập liệu vào container
        $("#itemsContainer").append(itemDiv);

        // Cập nhật giá trị tổng khi thêm mới một mục
        updateTotalItems();
    }

    // Hàm cập nhật giá trị tổng của itemsArray
    function updateTotalItems() {
        var total = 0;
        itemsArray.forEach(function(item) {
            total += parseInt(item.quantity);
        });

        // Hiển thị giá trị tổng trong ô nhập liệu giá trị tổng
        $("#totalItems").val(total);
    }

    // Hàm kiểm tra và cập nhật giá trị itemsArray trước khi gửi form
    function validateForm() {
        // Cập nhật giá trị của itemsArray
        updateItemsArray();
        return true; // Cho phép form được gửi đi
    }

    // Hàm cập nhật giá trị của itemsArray từ các trường nhập liệu
    function updateItemsArray() {
        itemsArray = [];

        // Lặp qua các div chứa trường nhập liệu và tạo mục cho mỗi div
        $("#itemsContainer div").each(function() {
            var id = parseInt($(this).find("input[name='id[]']").val());
            var expire = parseInt($(this).find("input[name='expire[]']").val()) || 0;
            var sys = parseInt($(this).find("input[name='sys[]']").val()) || 0;
            var quantity = parseInt($(this).find("input[name='quantity[]']").val()) || 0;
            var options = $(this).find("input[name='options[]']").val() || '[]';
            var isLock = $(this).find("select[name='isLock[]']").val() === 'true';

            // Kiểm tra xem options có phải là một chuỗi JSON hợp lệ không
            try {
                JSON.parse(options);
            } catch (e) {
                options = '[]'; // Nếu không hợp lệ, gán giá trị mặc định
            }

            // Tạo một đối tượng mới và thêm vào mảng
            var newItem = {
                id: id,
                expire: expire,
                sys: sys,
                quantity: quantity,
                options: JSON.parse(options),
                isLock: isLock
            };

            itemsArray.push(newItem);
        });

        // Cập nhật giá trị của trường items_data
        $("#items_data").val(JSON.stringify(itemsArray));

        // Cập nhật giá trị tổng khi cập nhật itemsArray
        updateTotalItems();
    }
</script>

<?php
include_once './end.php';
?>