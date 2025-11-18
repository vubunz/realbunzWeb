<?php
include_once './main.php';

// Assume $row['username'] contains the username value

// Thực hiện truy vấn SQL để lấy dữ liệu từ bảng atm_bank
$query = "SELECT * FROM atm_bank WHERE message LIKE '%{$row['username']}%'";
$result = $conn->query($query);

// Kiểm tra nếu có bản ghi
if (mysqli_num_rows($result) > 0) {
?>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <div class="row text-center justify-content-center row-cols-3 row-cols-lg-6 g-1 g-lg-1">
                    <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold false" href="/profile" style="background-color: rgb(255, 180, 115);">Tài khoản</a></div>
                    <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold active" href="/lich-su" style="background-color: rgb(255, 180, 115);">Lịch sử GD</a></div>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-5 dataTable no-footer" role="table">
                    <thead>
                        <tr class="text-start fw-bold text-uppercase gs-0">
                            <th colspan="1" role="columnheader" class="table-sort-desc text-primary" style="cursor: pointer">TranId</th>
                            <th colspan="1" role="columnheader" class="" style="cursor: pointer">Số tiền</th>
                            <!-- <th colspan="1" role="columnheader" class="" style="cursor: default">Sau G.D</th> -->
                            <th colspan="1" role="columnheader" class="" style="cursor: default">Mô tả</th>
                            <th colspan="1" role="columnheader" class="" style="cursor: pointer">Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold" role="rowgroup" id="list-transaction">
                        <?php
                        while ($row_atm = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?php echo $row_atm['tranid']; ?></td>
                                <td><?php echo $row_atm['amount']; ?></td>
                                <!-- <td><?php echo $row['coin']; ?></td> -->
                                <td><?php echo $row_atm['message']; ?></td>
                                <td><?php echo $row_atm['created_at']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <hr>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-5 dataTable no-footer" role="table">
                    <thead>
                        <tr class="text-start fw-bold text-uppercase gs-0">
                            <th colspan="1" role="columnheader" class="table-sort-desc text-primary" style="cursor: pointer">TranId</th>
                            <th colspan="1" role="columnheader" class="" style="cursor: pointer">Số tiền</th>
                            <th colspan="1" role="columnheader" class="" style="cursor: default">Sau G.D</th>
                            <th colspan="1" role="columnheader" class="" style="cursor: default">Mô tả</th>
                            <th colspan="1" role="columnheader" class="" style="cursor: pointer">Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold" role="rowgroup" id="list-transaction">
                        <?php
                        while ($row_atm = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?php echo $row_atm['tranid']; ?></td>
                                <td><?php echo $row_atm['amount']; ?></td>
                                <td><?php echo $row['coin']; ?></td>
                                <td><?php echo $row_atm['message']; ?></td>
                                <td><?php echo $row_atm['created_at']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="d-flex text-center w-100 align-content-center justify-content-center">
        <div class="card">
            <div class="card-body">
                Không có bản ghi nào
            </div>
        </div>
    </div>
<?php
}

include_once './end.php';
?>