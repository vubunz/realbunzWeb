function verifyOTP() {
    var username = $("#fusername").val();
    var otp = $("#fcode").val();

    $.ajax({
        type: "POST",
        url: "verify_otp.php",
        dataType: 'json',
        data: {
            fusername: username,
            fcode: otp,
            action: "verifyOTP"
        },
        success: function (data, textStatus, xhr) {
            console.log("verifyOTP function success.", data);

            if (data && data.code == "00") {
                $("#forgotpass").hide();
                $("#changePass").show();
            } else {
                let alertErr = '<div class="alert alert-danger" id="error">Mã OTP không đúng.</div>';
                $("#notificationArea").empty().append(alertErr);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + error);
            displayNotification("Có lỗi xảy ra khi gửi yêu cầu.", "danger");
        }
    });
}

// Add other JavaScript functions as needed
