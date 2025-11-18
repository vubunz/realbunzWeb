<?php include_once 'main.php'; ?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #c8e1ff;
        text-align: left;
        padding: 12px;
        font-weight: bold;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:nth-child(odd) {
        background-color: #ffffff;
    }

    .ranking-title {
        font-size: 24px;
        font-weight: bold;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .highlight-1 {
        background-color: #FFD700;
    }

    .highlight-2 {
        background-color: #C0C0C0;
    }

    .highlight-3 {
        background-color: #cd7f32;
    }

    .highlight-4 {
        background-color: #ff4500;
    }

    .highlight-5 {
        background-color: #00BFFF;
    }

    /* Responsive Styles */
    @media only screen and (max-width: 600px) {
        th, td {
            padding: 8px; /* Giảm độ dày của đệm cho thiết bị di động */
        }
    }
</style>

<div class="text-center card">
    <div class="card-body">
        <div class="text-center ranking-title">BẢNG XẾP HẠNG TOP NẠP</div>
        <?php
        include './CMain/connect.php';

        // Truy vấn SQL để lấy thông tin từ bảng 'users' và xếp theo tổng nạp (tongnap) từ cao xuống thấp
        $sql = "SELECT username, vip, nap FROM player ORDER BY nap DESC /*LIMIT 20*/";
        $result = $conn->query($sql);
        ?>

        <table>
            <tr>
                <th>TOP</th>
                <th>Tài khoản</th>
                <th>VIP</th>
                <th>Tổng nạp</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                $top = 1;
                while ($row = $result->fetch_assoc()) {
                    $highlightClass = ($top <= 5) ? "highlight-$top" : "";
                    echo "<tr class='$highlightClass'>
                            <td>$top</td>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["vip"] . "</td>
                            <td>" . number_format($row["tongnap"]) . " Coin</td>
                        </tr>";
                    $top++;
                }
            } else {
                echo "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
            }
            ?>
        </table>

        <?php
        $conn->close();
        ?>
    </div>
</div>
<?php include_once './end.php'; ?>
