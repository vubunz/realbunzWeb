<div class="mb-2">
    <div class="row text-center justify-content-center row-cols-2 row-cols-lg-6 g-2 g-lg-2 mt-1">
        <div class="col">
            <div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" href="/" style="color: white;">Trang chủ</a></div>
        </div>
        <div class="col">
            <div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" href="/forum.php" style="color: white;">Diễn đàn</a></div>
        </div>
        <div class="col">
            <?php
            if (isset($_SESSION['username'])) {
                // Nếu đã đăng nhập, chuyển hướng bình thường
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" href="/recharge" style="color: white;">Nạp LCoin</a></div>';
            } else {
                // Nếu chưa đăng nhập, sử dụng modal
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalLogin" href="#" style="color: white;">Nạp LCoin</a></div>';
            }
            ?>
        </div>
        <div class="col">
            <!-- Đổi lượng -->
            <?php
            if (isset($_SESSION['username'])) {
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" href="/doicoin" style="color: white;">Đổi lượng</a></div>';
            } else {
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalLogin" href="#" style="color: white;">Đổi lượng</a></div>';
            }
            ?>
        </div>
        <div class="col">
            <!-- Đổi xu -->
            <?php
            if (isset($_SESSION['username'])) {
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" href="/doixu" style="color: white;">Đổi xu</a></div>';
            } else {
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalLogin" href="#" style="color: white;">Đổi xu</a></div>';
            }
            ?>
        </div>
        <div class="col">
            <!-- BXH -->
            <?php
            if (isset($_SESSION['username'])) {
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" href="/bxh" style="color: white;">Bảng Xếp Hạng</a></div>';
            } else {
                echo '<div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalLogin" href="#" style="color: white;">Bảng Xếp Hạng</a></div>';
            }
            ?>

        </div>

        <div class="col">
            <div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold false" href="<?php echo $configNapTien['zalo']['group']; ?>" style="color: white;">Box Zalo</a></div>
        </div>
        <div class="col">
            <div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold false" href="/download" style="color: white;">Tải game</a></div>
        </div>

        <!--<div class="col">
            <div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold false" href="//www.youtube.com/">Tiktok(Live)</a></div>
        </div>-->
        <?php
        include_once 'f3269rfkv.php';

        if (isset($_SESSION['username']) && checkAdmin($conn, $_SESSION['username'])) {
            echo '<div class="col"><div class="px-2"><a class="btn btn-menu btn-dangerxyz w-100 fw-semibold false" href="/fdfgg11989944" style="color: white;">Panel điều khiển</a></div></div>';
        }
        ?>
    </div>
</div>