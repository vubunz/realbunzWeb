<?php
if (!isset($_SESSION['username'])) {
   header('Location: /');
}
?>
<style>
   .kichanh {
      width: 300px;
   }
</style>
<?php include_once './main.php'; ?>
<div class="d-flex justify-content-center">
   <div class="col-md-8 mt-3">
      <div class="fs-5 fw-semibold text-center">Chọn mốc nạp</div>
      <div>
         <div id="list_amt" class="row text-center justify-content-center row-cols-2 row-cols-lg-3 g-2 g-lg-2 my-1 mb-2">
            <?php
            foreach ($list_recharge_price_momo as $item) {
               if ($item['bonus'] > 0) {
                  echo '<div>
                     <div class="col">
                        <div class="w-100 fw-semibold cursor-pointer">
                           <div class="recharge-method-item position-relative false" style="height: 90px;">
                              <div>' . number_format($item['amount']) . ' đ</div>
                              <div class="center-text text-danger"><span>Nhận</span></div>
                              <div class="text-primary">' . number_format($item['amount'] + ($item['amount'] * $item['bonus'] / 100)) . ' LCoin </div>
                              <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="z-index: 1;">+' . $item['bonus'] . '%</span>
                           </div>
                        </div>
                     </div>
                  </div>';
               } else {
                  echo '<div>
                     <div class="col">
                        <div class="w-100 fw-semibold cursor-pointer">
                           <div class="recharge-method-item position-relative false" style="height: 90px;">
                              <div>' . number_format($item['amount']) . ' đ</div>
                              <div class="center-text text-danger"><span>Nhận</span></div>
                              <div class="text-primary">' . number_format($item['amount']) . ' LCoin </div>
                           </div>
                        </div>
                     </div>
                  </div>';
               }
            }
            ?>
         </div>
         <div id="momo_info"></div>
         <div class="text-center mt-3 momo-btn">
            <button type="button" id="payment_momo" class="w-50 rounded-3 btn btn-primary btn-sm">Thanh toán</button>
            <button type="button" id="confirm_payment_momo" class="w-50 rounded-3 btn btn-primary btn-sm hide">Xác nhận (<span id="count"></span>)</button>
            <div class="mt-2"><small class="fw-semibold"><a href="./lich-su">Lịch sử giao dịch</a></small></div>
         </div>
         <div class="mt-4"><small class="fw-semibold">Lưu ý khi thanh toán: Giao dịch trên hoàn toàn được kiểm duyệt tự động, Yêu cầu kiểm tra kỹ nội dung chuyển tiền trước khi thực hiện chuyển. Nếu ghi thiếu, sai hoặc quá 10 phút không thấy cộng tiền, các bạn hãy liên hệ với <a target="_blank" href="https://zalo.me/<?php echo $configNapTien['momo']['sotaikhoan']; ?>" rel="noreferrer">Admin</a> để được hỗ trợ</small></div>
         <div class="mt-4">
            <small class="fw-semibold">
               Việc <strong>ủng hộ (donate)</strong> để nhận coin là <strong>hoàn toàn tự nguyện</strong>, không phải hình thức mua bán hay trao đổi dịch vụ. Nghiêm cấm mọi hành vi vi phạm pháp luật như rửa tiền, gian lận,... Nếu phát sinh sự cố, vui lòng liên hệ <a target="_blank" href="https://zalo.me/<?php echo $configNapTien['momo']['sotaikhoan']; ?>" rel="noreferrer">Admin</a> để được hỗ trợ kịp thời.
            </small>
         </div>

      </div>
   </div>
</div>

<script>
   (function() {
      'use strict'
      var selected = -1;
      $("#list_amt div.recharge-method-item").each(function() {
         var item = this;
         item.addEventListener("click", function() {
            event.preventDefault();
            console.log($("#list_amt div.recharge-method-item").index(this))
            selected = $("#list_amt div.recharge-method-item").index(this)
            $("#list_amt div.recharge-method-item").removeClass("active")
            $("#list_amt div.recharge-method-item").addClass("false")
            $(this).removeClass("false")
            $(this).addClass("active")
         })
      })
      var btnPaymentMomo = $("button#payment_momo");
      var btnConfirmPaymentMomo = $("button#confirm_payment_momo");
      var spanCountdown = $("button#confirm_payment_momo span#count");
      var infoPaymentMomo = $("div#momo_info");
      var counter = 600;
      btnPaymentMomo.click(() => {
         $("#NotiflixLoadingWrap").removeClass('hide');
         var err = $("div.momo-btn div#error").first()
         if (err) err.remove()
         $.ajax({
            url: "./api-momo.php",
            type: "POST",
            dataType: "json",
            data: JSON.stringify({
               pcoin: selected
            }),
            success: function(data) {
               $("#NotiflixLoadingWrap").addClass('hide');
               if (data.code == "00") {

                  let atm_img = data.qr_atm;
                  let tien_img = data.gia_pay;
                  let namebank = data.name_pay;
                  let stkbank = data.stk_pay;
                  let nhbank = data.nh_pay;

                  infoPaymentMomo.append("<div class=\"text-center fw-semibold fs-5\" id=\"generate_info\">Nội Dung Chuyển Khoản</div><div class=\"text-center mt-2\"><img class=\"kichanh\" src='" + atm_img + "'></div><div class=\"text-center mt-2\"><div class=\"center-text fs-6 fw-semibold\"></div>")
                  infoPaymentMomo.append("<div id=\"momo_info\" class=\"table-responsive-sm\"> \
                        <table class=\"table\"> \
                        <tbody> \
                            <tr> \
                                <td><h6>Ngân hàng</h6></td> \
                                <td><h6>" + nhbank + "</h6></td> \
                            </tr> \
                            <tr> \
                                <td><h6>Chủ Tài Khoản</h6></td> \
                                <td><h6>" + namebank + "</h6></td> \
                            </tr> \
                            <tr> \
                                <td><h6>Số Tài Khoản</h6></td> \
                                <td><h6>" + stkbank + "</h6></td> \
                            </tr> \
                            <tr> \
                                <td><h6>Số Tiền</h6></td> \
                                <td><h6>" + tien_img + " vnđ</h6></td> \
                            </tr> \
                            <tr> \
                                <td><h6>Nội Dung</h6></td> \
                                <td><h6>nt <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Trống'; ?></h6></td> \
                            </tr> \
                        </tbody> \
                        </table> \
                    </div>");
                  //infoPaymentMomo.append("<div class=\"text-center fw-semibold fs-7\" id=\"generate_info\">Lưu ý: Nhập Đúng Nội Dung Chuyển Khoản. Có 2 Chức Năng Nạp MOMO và ATM Hãy Nạp Đúng Thứ Mình Đang Dùng...(Chuyển Khoản Xong Bấm Xác Nhận)</div>")

                  btnPaymentMomo.addClass("hide");
                  btnConfirmPaymentMomo.removeClass("hide");
                  counter = 600;
                  setInterval(function() {
                     counter--;
                     if (counter >= 0) {
                        spanCountdown.html(counter);
                     }
                     if (counter === 0) {
                        $("div#momo_info").empty();
                        btnPaymentMomo.removeClass("hide");
                        btnConfirmPaymentMomo.addClass("hide");
                        clearInterval(counter);
                     }
                  }, 1000);
               } else {
                  let alertNoti =
                     `<div class="alert alert-danger" id="error">` +
                     data.text +
                     `</div>`;
                  $("div.momo-btn").prepend(alertNoti);
               }
            },
            error: function(xhr, textStatus, errorThrown) {
               $("#NotiflixLoadingWrap").addClass('hide');
            },
         });
      });

      btnConfirmPaymentMomo.click(() => {
         $("#NotiflixLoadingWrap").removeClass('hide');
         $.ajax({
            url: "./sepay_callback/api_transactions.php",
            type: "POST",
            dataType: "json",
            data: JSON.stringify({
               pcoin: selected
            }),
            success: function(data) {
               $("div#momo_info").empty();
               btnPaymentMomo.removeClass("hide");
               btnConfirmPaymentMomo.addClass("hide");
               clearInterval(counter);
               $("#NotiflixLoadingWrap").addClass('hide');
               if (data.success) {
                  alert("Nạp tiền thành công!");
               } else {
                  alert(data.message || "Không tìm thấy giao dịch phù hợp. Vui lòng thử lại sau vài phút.");
               }
            },
            error: function(xhr, textStatus, errorThrown) {
               $("div#momo_info").empty();
               btnPaymentMomo.removeClass("hide");
               btnConfirmPaymentMomo.addClass("hide");
               $("#NotiflixLoadingWrap").addClass('hide');
               alert("Có lỗi xảy ra, vui lòng thử lại.");
            },
         });
      })
   })()
</script>