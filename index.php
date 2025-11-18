<?php
include_once './main.php';



// Kiểm tra xem trang có được làm mới không
$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['username'])) {
    // Nếu trang đã được làm mới, lấy dữ liệu người dùng từ cơ sở dữ liệu
    if ($pageWasRefreshed) {


        // Làm sạch username để tránh SQL injection
        $safeUsername = mysqli_real_escape_string($conn, $_SESSION['username']);

        // Lấy dữ liệu người dùng từ cơ sở dữ liệu
        $sql = "SELECT * FROM users WHERE username = '$safeUsername'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Trích xuất các giá trị cụ thể và lưu trữ chúng trong biến phiên
                    $_SESSION['username'] = $row['username'];
                    // Thêm nhiều trường khác nếu cần
                }
            }
        }
    }
}

// Lấy trang, tab và action từ URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$tab = isset($_GET['tab']) ? $_GET['tab'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>



<body>
    <div class="overlay"></div>
    <div id="fb-root"></div>

    <?php include_once './loading.php'; ?>

    <div class="card">
        <div class="card-body">
            <?php
            if ($page == 'post') {
                include("./post.php");
            } elseif ($page == 'category') {
                include("./category.php");
            } elseif ($page == 'rps-war') {
                include("./rps_war.php");
            } else {
                include("./hom.php");
            }
            ?>
        </div>
    </div>

    <?php include_once './end.php'; ?>

    <script>
        // Show loading
        $(document).on({
            ajaxStart: function() {
                // $("body").addClass("loading");
                $("#NotiflixLoadingWrap").removeClass('hide')
            },
            ajaxStop: function() {
                // $("body").removeClass("loading");
                $("#NotiflixLoadingWrap").addClass('hide')
            },
        });
    </script>
</body>