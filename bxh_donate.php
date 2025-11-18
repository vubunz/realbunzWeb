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
				<div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold false" href="/bxh" style="background-color: rgb(255, 180, 115);">BXH Level</a></div>
				<div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold active" href="/bxh_donate" style="background-color: rgb(255, 180, 115);">TOP Donate</a></div>
			</div>
		</div>
		<h5 class="card-header" style='text-align:center; color: #0096ff;'>Bảng Xếp Hạng Donate</h5>
		<div class="table-responsive">
			<table class="table table-hover table-nowrap">

				<thead>
					<tr>
						<th style='text-align:center; font-size: 15px'>TOP</th>
						<th style='text-align:center; font-size: 15px'>Nhân vật</th>
						<!-- <th style='text-align:center; font-size: 15px'>Phái</th> -->
						<th style='text-align:center; font-size: 15px'>Tổng Nạp</th>

					</tr>
				</thead>

				<tbody>
					<?php
					/* Kết nối SQL */
					require_once("CMain/connect.php");

					// JOIN 2 bảng users và players để lấy thông tin donate và tên nhân vật
					// Sử dụng prepared statement để tránh SQL injection
					$sql = "SELECT u.tongnap, p.name, p.class 
							FROM `users` u 
							LEFT JOIN `players` p ON u.id = p.user_id 
							WHERE u.tongnap > 0 
							ORDER BY u.tongnap DESC 
							LIMIT ?";

					// Sử dụng prepared statement
					$stmt = mysqli_prepare($conn, $sql);
					if ($stmt) {
						$limit = 20;
						mysqli_stmt_bind_param($stmt, "i", $limit);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);

						$ranking = 1;
						while ($row = mysqli_fetch_array($result)) {
							// Xác định class
							$class = getClass($row['class']);

							// Hiển thị tên nhân vật, nếu không có thì hiển thị "Không xác định"
							$character_name = $row['name'] ? htmlspecialchars($row['name']) : "Không xác định";

							if ($ranking == 1) {
								echo "<tr>";
								echo "<td style='text-align:center; color:red'><b>[TOP " . $ranking . "]</b></td>";
								echo "<td style='text-align:center; color:red'><b>" . $character_name . "</b></td>";
								// echo "<td style='text-align:center; color:red'><b>" . $class . "</b></td>";
								echo "<td style='text-align:center; color:red'><b>" . number_format($row['tongnap']) . "</b></td>";
								echo "</tr>";
							} else if ($ranking == 2) {
								echo "<tr>";
								echo "<td style='text-align:center; color:blue'><b>[TOP " . $ranking . "]</b></td>";
								echo "<td style='text-align:center; color:blue'><b>" . $character_name . "</b></td>";
								// echo "<td style='text-align:center; color:blue'><b>" . $class . "</b></td>";
								echo "<td style='text-align:center; color:blue'><b>" . number_format($row['tongnap']) . "</b></td>";
								echo "</tr>";
							} else if ($ranking == 3) {
								echo "<tr>";
								echo "<td style='text-align:center; color:#0096ff'><b>[TOP " . $ranking . "]</b></td>";
								echo "<td style='text-align:center; color:#0096ff'><b>" . $character_name . "</b></td>";
								// echo "<td style='text-align:center; color:#0096ff'><b>" . $class . "</b></td>";
								echo "<td style='text-align:center; color:#0096ff'><b>" . number_format($row['tongnap']) . "</b></td>";
								echo "</tr>";
							} else {
								echo "<tr>";
								echo "<td style='text-align:center; color:#008080'><b>#" . $ranking . "</b></td>";
								echo "<td style='text-align:center; color:#008080'>" . $character_name . "</td>";
								// echo "<td style='text-align:center; color:#008080'>" . $class . "</td>";
								echo "<td style='text-align:center; color:#008080'>" . number_format($row['tongnap']) . "</td>";
								echo "</tr>";
							}
							$ranking++;
						}

						mysqli_stmt_close($stmt);
					} else {
						echo "<tr><td colspan='4' style='text-align:center; color:red;'>Lỗi truy vấn dữ liệu</td></tr>";
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