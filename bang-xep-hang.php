<?php
$_title = "DPVZ - Thanh Toán";
include './main.php';
//
$_alert = null;
?>
<main class="flex-grow-1 flex-shrink-1">
	<div class="container">
		<br>
		<div class="card mb-3">
			<div class="card-header">Bảng xếp hạng sự kiện</div>
			<div class="table-responsive">
				<table class="table table-hover table-nowrap">

					<thead>
						<tr>
							<th style='text-align:center; font-size: 15px'>TOP</th>
							<th style='text-align:center; font-size: 15px'>Characters</th>
							<th style='text-align:center; font-size: 15px'>Level</th>
							<th style='text-align:center; font-size: 15px'>Event Points</th>
						</tr>
					</thead>

					<tbody>
						<?php
						/* Kết nối SQL */
						$mysqli = new mysqli("localhost", "root", "", "legacy");

						// Kiểm tra kết nối
						if ($mysqli === false) {
							die("ERROR: Không thể kết nối. " . $mysqli->connect_error);
						}


						// Cố gắng thực thi truy vấn với mệnh đề ORDER BY
						// Mặc định sắp xếp theo thứ tự tăng dần
						$sql = "SELECT * FROM ninja ORDER BY pointEvent DESC LIMIT 10";
						$ranking = 1;

						if ($result = $mysqli->query($sql)) {
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_array()) {
									if ($ranking == 1) {
										echo "<tr>";
										echo "<td style='text-align:center; color:red'><b>[TOP " . $ranking . "]</b></td>";
										echo "<td style='text-align:center; color:red'><b>" . $row['name'] . "</b></td>";
										echo "<td style='text-align:center; color:red'><b>" . $row['level'] . "</b></td>";
										echo "<td style='text-align:center; color:red'><b>" . $row['pointEvent'] . " Points</b></td>";
										echo "</tr>";
									} else if ($ranking == 2) {
										echo "<tr>";
										echo "<td style='text-align:center; color:blue'><b>[TOP " . $ranking . "]</b></td>";
										echo "<td style='text-align:center; color:blue'><b>" . $row['name'] . "</b></td>";
										echo "<td style='text-align:center; color:blue'><b>" . $row['level'] . "</b></td>";
										echo "<td style='text-align:center; color:blue'><b>" . $row['pointEvent'] . " Points</b></td>";
										echo "</tr>";
									} else if ($ranking == 3) {
										echo "<tr>";
										echo "<td style='text-align:center; color:green'><b>[TOP " . $ranking . "]</b></td>";
										echo "<td style='text-align:center; color:green'><b>" . $row['name'] . "</b></td>";
										echo "<td style='text-align:center; color:green'><b>" . $row['level'] . "</b></td>";
										echo "<td style='text-align:center; color:green'><b>" . $row['pointEvent'] . " Points</b></td>";
										echo "</tr>";
									} else {
										echo "<tr>";
										echo "<td style='text-align:center; color:#008080'><b>#" . $ranking . "</b></td>";
										echo "<td style='text-align:center; color:#008080'>" . $row['name'] . "</td>";
										echo "<td style='text-align:center; color:#008080'>" . $row['level'] . "</td>";
										echo "<td style='text-align:center; color:#008080'>" . $row['pointEvent'] . " Points</td>";
										echo "</tr>";
									}
									$ranking++;
								}
								// Giải phóng bộ nhớ của biến
								$result->free();
							} else {
								echo "<center>Không có nhân vật nào được tìm thấy</center>";
							}
						} else {
							echo "ERROR: Không thể thực thi $sql. " . $mysqli->error;
						}

						// Đóng kết nối
						$mysqli->close();
						?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
	<div class="py-3 text-center">
		<?php
		include_once 'end.php';
		?>
	</div>
	</div>
</main