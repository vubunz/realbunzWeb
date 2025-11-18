<?php
ob_start();
include './main.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: /');
    exit();
}
ob_end_flush();
?>
<div class="card" style="">
    <div class="card-body">
        <?php include('success.php'); ?>
        <?php include('error.php'); ?>


        <div class="mb-3">
            <div class="row text-center justify-content-center row-cols-3 row-cols-lg-6 g-1 g-lg-1">
                <div class="col">
                    <a class="btn btn-sm btn-info w-100 fw-semibold active" href="/profile" style="background-color: rgb(101, 172, 173); color: white;">Tài khoản</a>
                </div>
                <div class="col">
                    <a class="btn btn-sm btn-info w-100 fw-semibold false" href="/lich-su" style="background-color: rgb(101, 172, 173); color: white;">Lịch sử GD</a>
                </div>
            </div>
        </div>
        <?php

        if (isset($_POST["submit"])) {
            $old_phone = $_POST["old_phone"];
            $new_phone = $_POST["phone"];

            // Kiểm tra tính hợp lệ của Mã
            if ($old_phone == "") {
                $_SESSION['error'] = "Mã không được để trống.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            } elseif (!is_numeric($old_phone)) {
                $_SESSION['error'] = "Mã không hợp lệ.";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit(0);
            } else {
                // Kiểm tra xem Mã có trùng khớp với số điện thoại hiện tại trong cơ sở dữ liệu không
                $kiem_tra_so_dien_thoai_sql = "SELECT * FROM `users` WHERE `username` = '" . $_SESSION['username'] . "' AND `phone` = '$old_phone'";
                $kiem_tra_so_dien_thoai_query = mysqli_query($conn, $kiem_tra_so_dien_thoai_sql);

                if (mysqli_num_rows($kiem_tra_so_dien_thoai_query) > 0) {
                    // Mã là chính xác, tiếp tục thực hiện cập nhật
                    $cap_nhat_so_dien_thoai_sql = "UPDATE `users` SET `phone` = '$new_phone' WHERE `username` = '" . $_SESSION['username'] . "'";
                    $cap_nhat_so_dien_thoai_query = mysqli_query($conn, $cap_nhat_so_dien_thoai_sql);

                    if ($cap_nhat_so_dien_thoai_query) {
                        $_SESSION['success'] = "Đổi số điện thoại thành công.";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                    } else {
                        $_SESSION['error'] = "Đổi mã thất bại, Vui lòng liên hệ ADMIN.";
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit(0);
                    }
                } else {
                    $_SESSION['error'] = "Mã Sai.";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit(0);
                }
            }
        }
        ?>
        <hr>
        <div class="w-100 d-flex justify-content-center">
            <form class="pb-3" method="POST" action="change-phone-number">
                <div class="mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-solid" name="old_phone" id="old_phone" placeholder="Nhập mã (SĐT Cũ)" required>
                    </div>
                </div>


                <div class="mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-solid" name="phone" id="phone" placeholder="Nhập số điện thoại mới" required>
                    </div>
                </div>
                <div class="text-center mt-3 d-flex justify-content-center">
                    <button class="me-3 btn btn-success" type="submit" name="submit" id="btn">Thực hiện</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal HTML -->
<div id="doiluong9ThanhCong9" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" onclick="closeDoiluong9Thanhcong9()">&times;</span>
            <div class="modal-body text-center">
                <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 150px;"></a>
                <h2>Đổi số điện thoại thành công</h2>
                <p id="doiluong9ThanhCong9Content"></p>
                <button class="modal-close-btn" onclick="closeDoiluong9ThanhCong9()">OK</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal HTML -->
<div id="doiluong9ThatBai9" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" onclick="closeDoiluong9ThatBai9()">&times;</span>
            <div class="modal-body text-center">
                <a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 150px;"></a>
                <h2>Thất bại</h2>
                <p id="doiluong9ThatBai9Content"></p>
                <button class="btn btn-danger btn-lg" onclick="closeDoiluong9ThatBai9()">OK</button>
            </div>
        </div>
    </div>
</div>


<style>
    /* CSS cho modal */
    .modal {
        /* ... */
        transition: opacity 0.3s ease-in-out;
        /* Thêm transition cho modal */
    }

    .modal.show {
        opacity: 1;
        /* Hiển thị modal mượt mà */
    }

    /* CSS cho nút OK */
    .modal-close-btn {
        background-color: #007BFF;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        transition: background-color 0.3s ease-in-out;
        /* Thêm transition cho nút OK */
    }

    /* Khi hover vào nút đóng */
    .modal-close-btn:hover {
        background-color: #0056b3;
    }
</style>


<script>
    function openDoiluong9ThatBai9() {
        var doiluong9ThatBai9 = document.getElementById('doiluong9ThatBai9');
        doiluong9ThatBai9.style.display = 'block';
    }

    function closeDoiluong9ThatBai9() {
        var doiluong9ThatBai9 = document.getElementById('doiluong9ThatBai9');
        doiluong9ThatBai9.style.display = 'none';
    }


    function openDoiluong9ThanhCong9() {
        var doiluong9ThanhCong9 = document.getElementById('doiluong9ThanhCong9');
        doiluong9Thanhcong9.style.display = 'block';
    }

    function closeDoiluong9ThanhCong9() {
        var doiluong9ThanhCong9 = document.getElementById('doiluong9ThanhCong9');
        doiluong9ThanhCong9.style.display = 'none';
    }
</script>

<!--
            <form method="POST" class="pb-3" style="width: 26rem;">
    <div class="fs-5 fw-bold text-center mb-3">Đổi mật khẩu</div>
    <div class="mb-2">
        <div class="input-group">
            <input name="password" type="text" autocomplete="off" placeholder="Nhập mật khẩu hiện tại" class="form-control form-control-solid" value="">
        </div>
    </div>
    <div class="mb-2">
        <div class="input-group">
            <input name="new_password" type="password" autocomplete="off" placeholder="Mật khẩu" class="form-control form-control-solid" value="" oninput="validatePassword(this)">
        </div>
    </div>
    <div class="mb-2">
        <div class="input-group">
            <input name="new_password_confirmation" type="password" autocomplete="off" placeholder="Nhập lại mật khẩu" class="form-control form-control-solid" value="" oninput="validatePassword(this)">
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="submit" class="me-3 btn btn-success" id="btn">Đổi mật khẩu</button>
    </div>
</form>-->
<?php include 'end.php'; ?>

</body>

</html>