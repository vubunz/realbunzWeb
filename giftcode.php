<?php
ob_start();
include "./main.php";
ob_end_flush();
?>

<style>
    #itemTable {
        border-collapse: collapse;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    #itemTable th,
    #itemTable td {
        padding: 12px;
        text-align: left;
        border-right: 1px solid #ff9899;
    }

    #itemTable tbody tr {
        border-bottom: 1px solid #ff9899;
    }

    #itemTable tbody tr:last-child {
        border-bottom: none;
    }

    #itemTable th:last-child,
    #itemTable td:last-child {
        border-right: none;
    }

    #itemTable thead th {
        background-color: #ff9899;
        color: white;
    }

    #itemTable th {
        background-color: #ff9899;
        color: white;
    }

    @media only screen and (max-width: 600px) {
        .search-container input[type="text"] {
            width: 100%;
        }
    }

    .card1 {
        width: 100%;
        overflow: hidden;
    }

    .card-body1 {
        overflow-x: auto;
    }
</style>

<div class="card">
    <center>
        <p>
        <h2 style="color: #ff8400;">🍑️Danh sách GiftCode🍑️</h2>
        </p>
    </center>
    <hr>
    <div class="card1">
        <div class="card-body1">
            <?php include('success.php'); ?>
            <?php include('error.php'); ?>
            <?php
            $show_delete_column = false;
            if (isset($_SESSION['username'])) {
                $sql = "SELECT role FROM users WHERE username = '" . $_SESSION['username'] . "'";
                $query = mysqli_query($conn, $sql);
                $num_rows = mysqli_num_rows($query);

                if ($num_rows > 0) {
                    $row = mysqli_fetch_array($query);
                    if ($row['role'] == 2002) {
                        $show_delete_column = true;
                    }
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["code"])) {
                    $codeToDelete = $_POST["code"];
                    $deleteQuery = "DELETE FROM gift_codes WHERE code = '$codeToDelete'";
                    if (checkAdmin($conn, $_SESSION['username'])) {
                        if ($conn->query($deleteQuery) === TRUE) {
                            $_SESSION['success'] = "Đã xoá!";
                            header("Location: " . $_SERVER['REQUEST_URI']);
                            exit(0);
                        } else {
                            $_SESSION['error'] = "Lỗi khi xoá mã!";
                            header("Location: " . $_SERVER['REQUEST_URI']);
                            exit(0);
                        }
                    } else {
                        $_SESSION['error'] = "Bạn không là gì để có thể xoá luôn ýyyy";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                    }
                }
            }

            $sql = "SELECT code, items, coin, yen, gold FROM gift_codes ORDER BY code";
            $result = $conn->query($sql);
            ?>

            <table id='itemTable'>
                <tr>
                    <th>Gift Code</th>
                    <th>Vật phẩm</th>
                    <th>Xu</th>
                    <th>Yên</th>
                    <th>Lượng</th>
                    <?php if ($show_delete_column): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>

                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["code"]) . "</td>";
                        echo "<td>";

                        if (!empty($row["items"])) {
                            $items = json_decode($row["items"], true);
                            if (is_array($items)) {
                                foreach ($items as $item) {
                                    if (isset($item['id'])) {
                                        $itemId = $item['id'];
                                        $expire = isset($item['expire']) ? $item['expire'] : -1;
                                        $soluong = $item['quantity'];

                                        $itemQuery = "SELECT name FROM item WHERE id = $itemId";
                                        $itemResult = $conn->query($itemQuery);

                                        if ($itemResult && $itemResult->num_rows > 0) {
                                            $itemName = $itemResult->fetch_assoc()['name'];
                                            echo htmlspecialchars($itemName);
                                            echo ", Số lượng: ";
                                            echo $soluong;
                                            echo ", Thời hạn: ";
                                            echo $expire == -1 ? "Vĩnh viễn" : "Có thời hạn";
                                            echo "<br>";
                                        }
                                    }
                                }
                            }
                        }

                        echo "</td>";
                        echo "<td>" . number_format($row["coin"]) . "</td>";
                        echo "<td>" . number_format($row["yen"]) . "</td>";
                        echo "<td>" . number_format($row["gold"]) . "</td>";

                        if ($show_delete_column) {
                            echo "<td><form method='post' action=''>";
                            echo "<input type='hidden' name='code' value='" . htmlspecialchars($row['code']) . "'>";
                            echo "<input type='submit' value='Xóa' class='btn btn-danger btn-sm'></form></td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>Không có giftcode nào</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<?php
include 'end.php';
?>