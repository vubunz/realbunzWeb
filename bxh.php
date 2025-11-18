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
						<th style='text-align:center; font-size: 15px'>Thời gian đạt level</th>
						<!-- <th style='text-align:center; font-size: 15px'>Kinh nghiệm</th> -->
					</tr>
				</thead>

				<tbody>
					<?php
					/* Kết nối SQL */
					require_once("CMain/connect.php");

					// Truy vấn tất cả players và parse JSON để lấy exp
					// Sử dụng prepared statement để tránh SQL injection
					$sql = "SELECT * FROM `players`";
					$stmt = mysqli_prepare($conn, $sql);

					if ($stmt) {
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);

						$players_with_exp = array();

						while ($row = mysqli_fetch_array($result)) {
							// Parse JSON từ cột data
							$data = json_decode($row['data'], true);

							if ($data && isset($data['exp'])) {
								$exp = $data['exp'];
								// Tính level dựa trên exp (bạn có thể điều chỉnh công thức này)
								$level = calculateLevel($exp);

								// Lấy thời gian đạt level
								$levelUpTime = isset($data['levelUpTime']) ? $data['levelUpTime'] : 0;

								$players_with_exp[] = array(
									'name' => htmlspecialchars($row['name']),
									'class' => $row['class'],
									'exp' => $exp,
									'level' => $level,
									'levelUpTime' => $levelUpTime
								);
							}
						}

						// Sắp xếp theo level giảm dần, nếu cùng level thì sắp xếp theo thời gian đạt level tăng dần (sớm nhất lên đầu)
						usort($players_with_exp, function ($a, $b) {
							// So sánh level trước
							if ($a['level'] != $b['level']) {
								return $b['level'] - $a['level']; // Level cao hơn xếp trước
							}
							// Nếu cùng level thì so sánh thời gian đạt level
							return $a['levelUpTime'] - $b['levelUpTime']; // Thời gian sớm hơn xếp trước
						});

						// Lấy top 20
						$top_players = array_slice($players_with_exp, 0, 20);

						$ranking = 1;
						foreach ($top_players as $player) {
							// Xác định class
							$class = getClass($player['class']);

							// Format thời gian đạt level
							$levelTime = formatLevelUpTime($player['levelUpTime']);

							if ($ranking == 1) {
								echo "<tr>";
								echo "<td style='text-align:center; color:red'><b>[TOP " . $ranking . "]</b></td>";
								echo "<td style='text-align:center; color:red'><b>" . $player['name'] . "</b></td>";
								echo "<td style='text-align:center; color:red'><b>" . $class . "</b></td>";
								echo "<td style='text-align:center; color:red'><b>" . $player['level'] . "</b></td>";
								echo "<td style='text-align:center; color:red'><b>" . $levelTime . "</b></td>";
								// echo "<td style='text-align:center; color:red'><b>" . number_format($player['exp']) . "</b></td>";
								echo "</tr>";
							} else if ($ranking == 2) {
								echo "<tr>";
								echo "<td style='text-align:center; color:blue'><b>[TOP " . $ranking . "]</b></td>";
								echo "<td style='text-align:center; color:blue'><b>" . $player['name'] . "</b></td>";
								echo "<td style='text-align:center; color:blue'><b>" . $class . "</b></td>";
								echo "<td style='text-align:center; color:blue'><b>" . $player['level'] . "</b></td>";
								echo "<td style='text-align:center; color:blue'><b>" . $levelTime . "</b></td>";
								// echo "<td style='text-align:center; color:blue'><b>" . number_format($player['exp']) . "</b></td>";
								echo "</tr>";
							} else if ($ranking == 3) {
								echo "<tr>";
								echo "<td style='text-align:center; color:#0096ff'><b>[TOP " . $ranking . "]</b></td>";
								echo "<td style='text-align:center; color:#0096ff'><b>" . $player['name'] . "</b></td>";
								echo "<td style='text-align:center; color:#0096ff'><b>" . $class . "</b></td>";
								echo "<td style='text-align:center; color:#0096ff'><b>" . $player['level'] . "</b></td>";
								echo "<td style='text-align:center; color:#0096ff'><b>" . $levelTime . "</b></td>";
								// echo "<td style='text-align:center; color:#0096ff'><b>" . number_format($player['exp']) . "</b></td>";
								echo "</tr>";
							} else {
								echo "<tr>";
								echo "<td style='text-align:center; color:#008080'><b>#" . $ranking . "</b></td>";
								echo "<td style='text-align:center; color:#008080'>" . $player['name'] . "</td>";
								echo "<td style='text-align:center; color:#008080'>" . $class . "</td>";
								echo "<td style='text-align:center; color:#008080'>" . $player['level'] . "</td>";
								echo "<td style='text-align:center; color:#008080'>" . $levelTime . "</td>";
								// echo "<td style='text-align:center; color:#008080'>" . number_format($player['exp']) . "</td>";
								echo "</tr>";
							}
							$ranking++;
						}

						mysqli_stmt_close($stmt);
					} else {
						echo "<tr><td colspan='6' style='text-align:center; color:red;'>Lỗi truy vấn dữ liệu</td></tr>";
					}

					// Hàm format thời gian đạt level
					function formatLevelUpTime($levelUpTime)
					{
						if ($levelUpTime <= 0) {
							return "Chưa có dữ liệu";
						}

						// Chuyển từ milliseconds sang seconds
						$timestamp = $levelUpTime / 1000;

						// Format theo định dạng Việt Nam
						return date('d/m/Y H:i:s', $timestamp);
					}

					// Hàm tính level dựa trên exp (tổng lũy kế từ bảng từng mốc EXP, đúng như mô tả)
					function calculateLevel($exp)
					{
						// Bảng từng mốc EXP (EXP cần để lên từng level tiếp theo)
						$exps = [200, 600, 1200, 2500, 5000, 9000, 18000, 20000, 24000, 36000, 54000, 64800, 90720, 127008, 177811, 248935, 261382, 339796, 441735, 574256, 497688, 597226, 716671, 860005, 1032006, 1238407, 1486089, 1783307, 2139968, 2567962, 4622331, 5546797, 6656157, 7987387, 12779820, 15335784, 18402942, 33125295, 39750354, 47700426, 152641360, 194617734, 247279005, 313220073, 395646410, 458514475, 526703913, 636228539, 784494868, 930618588, 950533224, 970323501, 991448556, 1011810700, 1034659751, 1058575992, 1078672354, 1098064208, 1119665920, 1452641360, 1814617734, 2272790205, 2732203073, 3256456410, 3881514475, 4563703913, 5366228539, 6144394868, 7030612388, 9000000000, 11000000000, 13000000000, 15000000000, 17500000000, 20000000000, 22500000000, 25000000000, 27500000000, 30000000000, 33000000000, 36000000000, 39000000000, 42000000000, 45500000000, 49000000000, 52500000000, 56000000000, 59500000000, 63000000000, 67000000000, 71000000000, 75000000000, 79000000000, 84000000000, 89000000000, 94000000000, 99000000000, 105000000000, 112000000000, 120000000000, 129000000000, 139000000000, 150000000000, 160000000000, 170000000000, 180000000000, 190000000000, 200000000000, 220000000000, 230000000000, 240000000000, 250000000000, 260000000000, 270000000000, 280000000000, 290000000000, 300000000000, 310000000000, 330000000000, 350000000000, 400000000000, 400000000000, 400000000000, 400000000000, 450000000000, 450000000000, 450000000000, 450000000000, 450000000000, 500000000000];
						$exp_total = [];
						$sum = 0;
						foreach ($exps as $e) {
							$sum += $e;
							$exp_total[] = $sum;
						}
						$level = 1;
						for ($i = 0; $i < count($exp_total); $i++) {
							if ($exp < $exp_total[$i]) {
								return $level;
							}
							$level++;
						}
						return $level;
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