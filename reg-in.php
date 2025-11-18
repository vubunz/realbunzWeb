<div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="modalRegisterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="my-2">
          <div class="text-center"><a href="/"><img class="logo" alt="Logo" src="images/1.gif" style="max-width: 200px;"></a></div>
        </div>
        <form action="#" method="post" class="py-3 mx-3 needs-validation" id="register">
          <div class="mb-2">
            <label class="fw-semibold">Tên đăng nhập</label>
            <div class="input-group"><input name="uname" id="uname" type="text" autocomplete="off" placeholder="Nhập tên đăng nhập" class="form-control form-control-solid" value=""></div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>
          <div class="mb-2">
            <label class="fw-semibold">Số Điện Thoại</label>
            <div class="input-group"><input name="phone" id="phone" type="phone" autocomplete="off" placeholder="Nhập phone" class="form-control form-control-solid" value=""></div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>
          <div class="mb-2">
            <label class="fw-semibold">Mật khẩu</label>
            <div class="input-group"><input name="passw" id="passw" type="password" autocomplete="off" placeholder="Nhập mật khẩu" class="form-control form-control-solid" value=""></div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>
          <div class="mb-2">
            <label class="fw-semibold">Nhập lại mật khẩu</label>
            <div class="input-group"><input name="repassw" id="repassw" type="password" autocomplete="off" placeholder="Nhập mật khẩu" class="form-control form-control-solid" value=""></div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>
          <div class="mb-2">
            <label class="fw-semibold">Nhập Email (không yêu cầu)</label>
            <div class="input-group"><input name="mail" id="mail" type="text" autocomplete="off" placeholder="Email dùng để quên pass" class="form-control form-control-solid" value=""></div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="me-3 btn btn-primary">Đăng ký</button><button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Hủy bỏ</button>
            <div class="pt-3">Với việc đăng ký tài khoản này, bạn đồng ý tuân thủ tất cả các <a href="/terms.php" class="link-primary cursor-pointer">Điều khoản và Chính sách</a> của máy chủ</div>
            <div class="pt-3">Bạn đã có tài khoản? <a data-bs-toggle="modal" data-bs-target="#modalLogin" class="link-primary cursor-pointer">Đăng nhập ngay</a></div>
            <div><a data-bs-toggle="modal" data-bs-target="#modalForgotPass" class="link-primary cursor-pointer">Quên mật khẩu</a></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    'use strict';

    function isValidString(str) {
      return /^[a-zA-Z0-9]+$/i.test(str);
    }

    function isValidPhone(phone) {
      return /^[0-9]+$/.test(phone);
    }

    function handleFormSubmission(event) {
      var err = $("form#register div#error").first();
      if (err) err.remove();

      event.preventDefault();
      event.stopPropagation();

      let check = true;

      var listInput = event.currentTarget.querySelectorAll('input');
      var listInValid = event.currentTarget.querySelectorAll('.invalid-feedback');

      listInput.forEach((item, index) => {
        if (!listInValid[index]) return;
        let val = item.value;
        if (index === 4) {
          listInValid[index].classList.remove('d-block');
          return;
        }
        if (val.trim().length == 0) {
          listInValid[index].innerHTML = "Không được để trống.";
          check = false;
          listInValid[index].classList.add('d-block');
        } else if (val.trim().length < 5) {
          listInValid[index].innerHTML = "Tối thiểu 5 ký tự.";
          check = false;
          listInValid[index].classList.add('d-block');
        } else if (index == 0 && !isValidString(val.trim())) {
          listInValid[index].innerHTML = "Tên đăng nhập không được chứa ký tự đặc biệt, ký tự hoa, hoặc khoảng trắng shop ơi!";
          check = false;
          listInValid[index].classList.add('d-block');
        } else if (index == 1 && !isValidPhone(val.trim())) {
          listInValid[index].innerHTML = "Số điện thoại không hợp lệ.";
          check = false;
          listInValid[index].classList.add('d-block');
        } else if (index == 3 && (val.trim() != listInput[index - 1].value.trim())) {
          listInValid[index].innerHTML = "Mật khẩu nhập lại không chính xác.";
          check = false;
          listInValid[index].classList.add('d-block');
        } else {
          listInValid[index].classList.remove('d-block');
        }
      });

      if (check) {
        let user_name = $('input#uname').val();
        let pass_word = $('input#passw').val();
        let phone = $('input#phone').val();
        let email = $('input#mail').val();
        $("#NotiflixLoadingWrap").removeClass('hide');
        $.ajax({
          url: './post-reg.php',
          type: 'POST',
          dataType: 'json',
          data: JSON.stringify({
            "username": user_name,
            "password": pass_word,
            "phone": phone,
            "email": email
          }),
          success: function(data, textStatus, xhr) {
            $("#NotiflixLoadingWrap").addClass('hide');
            if (data.code == "02") {
              let alertErr = '<div class="alert alert-danger" id="error">Tên đăng nhập đã tồn tại trên hệ thống.</div>';
              $("form#register").prepend(alertErr);
            } else if (data.code == "03") {
              let alertErr = '<div class="alert alert-danger" id="error">Số Điện Thoại đã tồn tại trên hệ thống.</div>';
              $("form#register").prepend(alertErr);
            } else if (data.code == "05") {
              let alertErr = '<div class="alert alert-danger" id="error">Tên đăng nhập không được chứa ký tự đặc biệt, ký tự hoa, hoặc khoảng trắng!</div>';
              $("form#register").prepend(alertErr);
            } else if (data.code != "00") {
              let alertErr = '<div class="alert alert-danger" id="error">Có lỗi xảy ra. Vui lòng liên hệ ADMIN để được hỗ trợ.</div>';
              $("form#register").prepend(alertErr);
            } else {
              let alertErr = '<div class="alert alert-success" id="error">Tạo tài khoản thành công. Bạn sẽ được chuyển hướng sau 3s.</div>';
              $("form#register").prepend(alertErr);
              setTimeout(() => {
                window.location.reload();
              }, 3000);
            }
          },
          error: function(xhr, textStatus, errorThrown) {
            $("#NotiflixLoadingWrap").addClass('hide');
            console.log('Error in Operation');
          }
        });
      }
    }

    function init() {
      var forms = document.querySelectorAll('.needs-validation#register');

      Array.prototype.slice.call(forms)
        .forEach(function(form) {
          form.addEventListener('submit', handleFormSubmission, false);
        });
    }

    // Khởi tạo khi trang tải xong
    window.addEventListener('load', init);
  })();
</script>