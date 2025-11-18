<?php
$_title = "TOP Level";
include_once 'main.php';
//
$_alert = null;
?>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="row text-center justify-content-center row-cols-3 row-cols-lg-6 g-1 g-lg-1">
                <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold active" href="/bxh" style="background-color: rgb(255, 180, 115);">BXH Level</a></div>
                <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold false" href="/bxh_donate" style="background-color: rgb(255, 180, 115);">TOP Donate</a></div>
            </div>
        </div>
        <h5 class="card-header" style='text-align:center; color: #0096ff;'>Bảng Xếp Hạng Level</h5>
        <div class="table-responsive">
            <table class="table table-hover table-nowrap">

                <thead>
                    <tr>
                        <th style='text-align:center; font-size: 15px'>TOP</th>
                        <th style='text-align:center; font-size: 15px'>Nhân vật</th>
                        <th style='text-align:center; font-size: 15px'>Phái</th>
                        <th style='text-align:center; font-size: 15px'>Level</th>
                        <th style='text-align:center; font-size: 15px'>Kinh nghiệm</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    /* Kết nối SQL */
                    require_once("CMain/connect.php");

                    // Truy vấn tất cả players và parse JSON để lấy exp
                    $sql = "SELECT * FROM `players`";
                    $query = mysqli_query($conn, $sql);

                    $players_with_exp = array();

                    while ($row = mysqli_fetch_array($query)) {
                        // Parse JSON từ cột data
                        $data = json_decode($row['data'], true);

                        if ($data && isset($data['exp'])) {
                            $exp = $data['exp'];
                            // Tính level dựa trên exp (bạn có thể điều chỉnh công thức này)
                            $level = calculateLevel($exp);

                            $players_with_exp[] = array(
                                'name' => $row['name'],
                                'class' => $row['class'],
                                'exp' => $exp,
                                'level' => $level
                            );
                        }
                    }

                    // Sắp xếp theo exp giảm dần
                    usort($players_with_exp, function ($a, $b) {
                        return $b['exp'] - $a['exp'];
                    });

                    // Lấy top 20
                    $top_players = array_slice($players_with_exp, 0, 20);

                    $ranking = 1;
                    foreach ($top_players as $player) {
                        // Xác định class
                        $class = getClass($player['class']);

                        if ($ranking == 1) {
                            echo "<tr>";
                            echo "<td style='text-align:center; color:red'><b>[TOP " . $ranking . "]</b></td>";
                            echo "<td style='text-align:center; color:red'><b>" . $player['name'] . "</b></td>";
                            echo "<td style='text-align:center; color:red'><b>" . $class . "</b></td>";
                            echo "<td style='text-align:center; color:red'><b>" . $player['level'] . "</b></td>";
                            echo "<td style='text-align:center; color:red'><b>" . number_format($player['exp']) . "</b></td>";
                            echo "</tr>";
                        } else if ($ranking == 2) {
                            echo "<tr>";
                            echo "<td style='text-align:center; color:blue'><b>[TOP " . $ranking . "]</b></td>";
                            echo "<td style='text-align:center; color:blue'><b>" . $player['name'] . "</b></td>";
                            echo "<td style='text-align:center; color:blue'><b>" . $class . "</b></td>";
                            echo "<td style='text-align:center; color:blue'><b>" . $player['level'] . "</b></td>";
                            echo "<td style='text-align:center; color:blue'><b>" . number_format($player['exp']) . "</b></td>";
                            echo "</tr>";
                        } else if ($ranking == 3) {
                            echo "<tr>";
                            echo "<td style='text-align:center; color:#0096ff'><b>[TOP " . $ranking . "]</b></td>";
                            echo "<td style='text-align:center; color:#0096ff'><b>" . $player['name'] . "</b></td>";
                            echo "<td style='text-align:center; color:#0096ff'><b>" . $class . "</b></td>";
                            echo "<td style='text-align:center; color:#0096ff'><b>" . $player['level'] . "</b></td>";
                            echo "<td style='text-align:center; color:#0096ff'><b>" . number_format($player['exp']) . "</b></td>";
                            echo "</tr>";
                        } else {
                            echo "<tr>";
                            echo "<td style='text-align:center; color:#008080'><b>#" . $ranking . "</b></td>";
                            echo "<td style='text-align:center; color:#008080'>" . $player['name'] . "</td>";
                            echo "<td style='text-align:center; color:#008080'>" . $class . "</td>";
                            echo "<td style='text-align:center; color:#008080'>" . $player['level'] . "</td>";
                            echo "<td style='text-align:center; color:#008080'>" . number_format($player['exp']) . "</td>";
                            echo "</tr>";
                        }
                        $ranking++;
                    }

                    // Hàm tính level dựa trên exp
                    function calculateLevel($exp)
                    {
                        // Bạn có thể điều chỉnh công thức này theo game của bạn
                        // Ví dụ: level = căn bậc 2 của exp / 1000
                        $level = floor(sqrt($exp / 1000));
                        return max(1, $level); // Đảm bảo level tối thiểu là 1
                    }

                    // Hàm xác định class
                    function getClass($class_id)
                    {
                        switch ($class_id) {
                            case 1:
                                return "Kiếm";
                            case 2:
                                return "Tiêu";
                            case 3:
                                return "Kunai";
                            case 4:
                                return "Cung";
                            case 5:
                                return "Đao";
                            case 6:
                                return "Quạt";
                            case 0:
                                return "Chưa Vào Lớp";
                            default:
                                return "Không xác định";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once 'end.php';
?>