<?php
?>
<div class="modal fade" id="modalForgotPass" tabindex="-1" aria-labelledby="modalForgotPassLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalForgotPassLabel">Quên mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="forgotPassForm">
                    <div class="mb-3">
                        <label for="forgotUsername" class="form-label">Nhập tên đăng nhập đã đăng ký</label>
                        <input type="text" class="form-control" id="forgotUsername" name="forgotUsername" placeholder="Nhập tên đăng nhập của bạn" required>
                        <div class="invalid-feedback">Vui lòng nhập tên đăng nhập hợp lệ.</div>
                    </div>

                    <!-- CAPTCHA Section -->
                    <div class="mb-3">
                        <label class="form-label">Xác thực bảo mật</label>
                        <div class="d-flex align-items-center gap-2">
                            <span id="captchaQuestion" class="fw-bold text-primary"></span>
                            <span>=</span>
                            <input type="number" class="form-control" id="captchaAnswer" name="captchaAnswer" placeholder="Kết quả" style="width: 100px;" required>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshCaptcha" title="Làm mới">
                                <i class="fas fa-sync-alt"></i> ↻
                            </button>
                        </div>
                        <div class="invalid-feedback">Vui lòng nhập kết quả chính xác.</div>
                        <small class="form-text text-muted">Giải phép tính để xác minh bạn không phải robot</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">Gửi lại mật khẩu</button>
                </form>
                <div id="forgotPassMsg" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var isSubmitting = false;
        var cooldownTime = 60; // Thời gian chờ 60 giây
        var cooldownTimer = null;
        var captchaAnswer = 0;

        // Tạo CAPTCHA
        function generateCaptcha() {
            var num1 = Math.floor(Math.random() * 10) + 1;
            var num2 = Math.floor(Math.random() * 10) + 1;
            var operators = ['+', '-', '×'];
            var operator = operators[Math.floor(Math.random() * operators.length)];

            var question = num1 + ' ' + operator + ' ' + num2;

            // Tính kết quả
            switch (operator) {
                case '+':
                    captchaAnswer = num1 + num2;
                    break;
                case '-':
                    captchaAnswer = num1 - num2;
                    break;
                case '×':
                    captchaAnswer = num1 * num2;
                    break;
            }

            $('#captchaQuestion').text(question);
            $('#captchaAnswer').val('').removeClass('is-invalid');
        }

        // Khởi tạo CAPTCHA khi trang load
        generateCaptcha();

        // Làm mới CAPTCHA
        $('#refreshCaptcha').on('click', function() {
            generateCaptcha();
        });

        function startCooldown() {
            var submitBtn = $('#submitBtn');
            var originalText = submitBtn.text();
            var timeLeft = cooldownTime;

            submitBtn.prop('disabled', true);
            submitBtn.text('Chờ ' + timeLeft + 's...');

            cooldownTimer = setInterval(function() {
                timeLeft--;
                submitBtn.text('Chờ ' + timeLeft + 's...');

                if (timeLeft <= 0) {
                    clearInterval(cooldownTimer);
                    submitBtn.prop('disabled', false);
                    submitBtn.text(originalText);
                }
            }, 1000);
        }

        $('#forgotPassForm').on('submit', function(e) {
            e.preventDefault();

            // Ngăn chặn submit nếu đang xử lý hoặc đang trong thời gian chờ
            if (isSubmitting || $('#submitBtn').prop('disabled')) {
                return;
            }

            var username = $('#forgotUsername').val().trim();
            var userCaptchaAnswer = parseInt($('#captchaAnswer').val());
            var msgBox = $('#forgotPassMsg');
            msgBox.html('');

            // Kiểm tra username
            if (username.length === 0) {
                $('#forgotUsername').addClass('is-invalid');
                return;
            } else {
                $('#forgotUsername').removeClass('is-invalid');
            }

            // Kiểm tra CAPTCHA
            if (userCaptchaAnswer !== captchaAnswer) {
                $('#captchaAnswer').addClass('is-invalid');
                msgBox.html('<div class="alert alert-danger">Kết quả CAPTCHA không chính xác. Vui lòng thử lại.</div>');
                generateCaptcha(); // Tạo CAPTCHA mới
                return;
            } else {
                $('#captchaAnswer').removeClass('is-invalid');
            }

            // Đánh dấu đang xử lý
            isSubmitting = true;
            var submitBtn = $('#submitBtn');
            var originalText = submitBtn.text();
            submitBtn.prop('disabled', true);
            submitBtn.text('Đang xử lý...');

            msgBox.html('<div class="text-info">Đang xử lý...</div>');

            $.ajax({
                url: 'post-forgot.php',
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify({
                    username: username
                }),
                success: function(res) {
                    if (res.code === '00') {
                        msgBox.html('<div class="alert alert-success">' + res.text + '</div>');
                        // Bắt đầu thời gian chờ sau khi gửi thành công
                        startCooldown();
                    } else {
                        msgBox.html('<div class="alert alert-danger">' + res.text + '</div>');
                        // Reset trạng thái nếu thất bại
                        isSubmitting = false;
                        submitBtn.prop('disabled', false);
                        submitBtn.text(originalText);
                    }
                },
                error: function() {
                    msgBox.html('<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại sau.</div>');
                    // Reset trạng thái nếu có lỗi
                    isSubmitting = false;
                    submitBtn.prop('disabled', false);
                    submitBtn.text(originalText);
                }
            });
        });

        // Reset trạng thái khi đóng modal
        $('#modalForgotPass').on('hidden.bs.modal', function() {
            isSubmitting = false;
            if (cooldownTimer) {
                clearInterval(cooldownTimer);
            }
            $('#submitBtn').prop('disabled', false).text('Gửi lại mật khẩu');
            $('#forgotPassMsg').html('');
            $('#forgotUsername').removeClass('is-invalid').val('');
            $('#captchaAnswer').removeClass('is-invalid').val('');
            generateCaptcha(); // Tạo CAPTCHA mới
        });
    });
</script>