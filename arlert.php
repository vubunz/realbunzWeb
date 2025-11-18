<?php
ob_start();

include_once './main.php';

if (!isset($_SESSION['username'])) {
    header('Location: /');
}
ob_end_flush();
?>

<style>
    /* Thêm kiểu tùy chỉnh của bạn ở đây */
    @keyframes blink {

        0%,
        100% {
            color: red;
            /* Màu chữ khi không nhấp nháy */
        }

        50% {
            color: transparent;
            /* Màu chữ khi nhấp nháy */
        }
    }

    #warningModal .text-center span.blinking {
        animation: blink 0.5s infinite;
        /* Áp dụng animation với 0.5 giây mỗi chu kỳ và vô hạn lặp lại */
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="card">
    <div class="card-body">
        <center>
            <h2 style="color: RED;">Địt cụ mày cút ngay vào đây làm gì ???</h2>
        </center>
        <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="my-2">
                            <div class="text-center"><a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 250px;"></a></div>
                        </div>
                        <div class="text-center fw-semibold">
                            <div id="noti" style="text-align: center;"></div>
                            <div class="text-white text-center mb-2" id="waiting-times"></div>
                            <h5 class="modal-title" id="warningModalLabel" style="color: red;">⚠️ Cảnh báo!</h5>
                            <div id="noti-active"></div>
                            <span class="blinking">Bạn đang truy cập vào trang quản trị dành cho Admin.</span>
                            <a class="w-100 fw-semibold" href="/nap-tien"></a>

                            <div class="mt-2 aci"><a href="/" class="btn btn-danger btn-lg" data-dismiss="modal">Về trang chủ</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script>
            $(document).ready(function() {
                $('#warningModal').modal('show');

                // Chuyển hướng khi bấm "OK"
                $('#warningModal').on('hidden.bs.modal', function() {
                    window.location.href = "/";
                });
            });
        </script>

    </div>
</div>
<?php include_once './end.php'; ?>