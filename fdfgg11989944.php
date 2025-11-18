<?php
// other_file.php

include_once 'f3269rfkv.php';
include_once './main.php';

if (!isset($_SESSION['username'])) {
    header('Location: /');
    exit();
}

if (!checkAdmin($conn, $_SESSION['username'])) {
    header('Location: /');
    exit();
}
?>



<div class="card">
    <div class="card-body">


        <style>
            .b-table tbody tr.active-row,
            .b-table tbody tr.active-row2 {
                font-weight: 500;
            }

            .b-table tbody tr.active-row {
                color: red;
            }

            .b-table tbody tr.active-row2 {
                color: black;
            }

            .button1 {
                background-color: white;
                color: black;
                border: 2px solid #ff9899;
                border-radius: 8px;
                /* Điều chỉnh giá trị này để thay đổi độ cong của góc */
            }

            .button1:hover {
                background-color: #ff9899;
                color: white;
            }

            #customTable {
                border-collapse: collapse;
                width: 100%;
                border-radius: 8px;
                /* Điều chỉnh giá trị này để thay đổi độ cong của góc */
                overflow: hidden;
            }

            #customTable th,
            #customTable td {
                padding: 12px;
                text-align: left;
                border-right: 1px solid #ff9899;
                /* Màu kẻ phân cách giữa các cột */
                height: 40px;
                /* Điều chỉnh chiều cao của hàng */
            }

            #customTable tbody tr {
                border-bottom: 1px solid #ff9899;
                /* Màu kẻ phân cách giữa các hàng */
                height: 40px;
                /* Điều chỉnh chiều cao của hàng */
            }

            #customTable tbody tr:last-child {
                border-bottom: none;
                /* Loại bỏ kẻ phân cách ở hàng cuối cùng */
            }

            #customTable th:last-child,
            #customTable td:last-child {
                border-right: none;
                /* Loại bỏ kẻ phân cách ở cột cuối cùng */
            }

            #customTable thead th {
                background-color: #ff9899;
                color: white;
            }

            /* Thêm đoạn mã CSS mới dưới đây */
            #customTable th {
                background-color: #ff9899;
                color: white;
                height: 40px;
                /* Điều chỉnh chiều cao của hàng */

            }

            #customTable tbody tr:nth-child(even) {
                background-color: #ffffff;
                /* Màu nền cho hàng chẵn */
            }

            #customTable tbody tr:nth-child(odd) {
                background-color: #f2f2f2;
                /* Màu nền cho hàng lẻ */
            }


            center {
                margin-top: 20px;
            }

            h2 {
                color: #ff8400;
                margin-bottom: 0;
            }

            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 12px;
                text-align: left;
                border: 1px solid #ddd;
            }

            th {
                background-color: #ff8400;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f1f1f1;
            }
        </style>

        <table class="b-table" id='customTable'>

            <thead>
                <tr>
                    <th>Chức năng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr class="active-row">
                    <td>Thêm GiftCode</td>
                    <td class="text-center"><a href="/axfgift"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Cộng Coin, Lượng thành viên</td>
                    <td class="text-center"><a href="/840893r32"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>Tìm tài khoản</td>
                    <td class="text-center"><a href="/r32r325"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Tìm nhân vật</td>
                    <td class="text-center"><a href="/fe4rf3r23r"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>Tìm tài khoản theo IP (Đang bảo trì)</td>
                    <td class="text-center"><a href="/3r32tger"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Chỉnh sửa thông báo game(Sửa trên file TXT JAVA)</td>
                    <td class="text-center"><a href="/alertxyz"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>Búp sạch nhân vật</td>
                    <td class="text-center"><a href="/084gr454"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Búp sạch Tài khoản</td>
                    <td class="text-center"><a href="/840fewf3"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>Thêm/Xoá Item WebShop</td>
                    <td class="text-center"><a href="/43tr34tf4"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Gửi đồ cho member</td>
                    <td class="text-center"><a href="/r3rewf43"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>Item Shốp Gô Sô Chậu</td>
                    <td class="text-center"><a href="/goso"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Thêm, sửa Bài viết</td>
                    <td class="text-center"><a href="/post-edit"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>Thêm, sửa Danh mục</td>
                    <td class="text-center"><a href="/dm-edit"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>Chỉnh sửa giới thiệu game</td>
                    <td class="text-center"><a href="/gioithieu"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>EFF</td>
                    <td class="text-center"><a href="/eff"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row2">
                    <td>EFF1</td>
                    <td class="text-center"><a href="/eff1"><button class="button button1">Thực hiện</button></a></td>
                </tr>
                <tr class="active-row">
                    <td>EFF2</td>
                    <td class="text-center"><a href="/eff2"><button class="button button1">Thực hiện</button></a></td>
                </tr>
            </tbody>
        </table>

        <?php
        $targetRole = '8763'; // Giá trị role cần hiển thị
        $sql = "SELECT username, role FROM users WHERE role = '$targetRole'";
        $result = $conn->query($sql);
        ?>
        <center>
            <p>
            <h2 style="color: #ff8400;">🍑️Danh sách ADMIN SEVER🍑️</h2>
            </p>
        </center>
        <?php //echo $targetRole; 
        ?>
        <table border="1">
            <tr>
                <th>Tài Khoản</th>
                <th>Role</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                <td>" . $row["username"] . "</td>
                <!--<td>" . $row["role"] . "</td>-->
                <td> Không thể xem Role </td>
                </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Chưa có ai là ADMIN</td></tr>";
            }
            ?>
        </table>
        <?php
        $conn->close();
        ?>

    </div>
</div>
<?php
include './end.php';
?>