<?php
ob_start();
include './main.php';


// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
  // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
  header('Location: /');
  exit(); // Đảm bảo dừng việc thực thi mã PHP sau khi chuyển hướng
}
ob_end_flush();



$_alert = null;

if (isset($_GET['act'])) {
  $act = htmlspecialchars($_GET['act']);
  if ($act == 'naptien') {
    $tenthe = isset_sql($_POST['naptienuid']);
    $menhgia = isset_sql($_POST['naptienname']);
    $seril = isset_sql($_POST['naptienSerial']);
    $manap = isset_sql($_POST['naptienCode']);
    $read = _fetch(_select('*', 'nap', "code='$manap' && name='$tenthe' && seri='$seril'"));
    $txt = _insert('nap', "uid,name,code,seri,number,user,time,tinhtrang", "'naptien','$tenthe','$manap','$seril','$menhgia','$_username','" . time() . "','wait'");
    $add = _query($txt);
    if ($add) {
      $_alert = _alert('succ', 'Đã thêm thẻ, vui lòng đợi kết quả');
    } else {
      $_alert = _alert('err', 'Có lỗi gì đó xảy ra, vui lòng kiểm tra thẻ hoặc liên hệ với admin !');
    }
  }
}
?>
<style>
  #NotiflixLoadingWrap {
    position: fixed;
    margin: auto;
    /*height: 2em;*/
    /*width: 2em;*/
    width: 0;
    height: 0;
    overflow: show;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
  }

  /* Transparent Overlay */
  #NotiflixLoadingWrap:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20, .1), rgba(0, 0, 0, .1));
  }

  .hide {
    display: none !important;
  }
</style>

<!------>

<script>
  function onClickNav(goto) {
    let isLogged = true
    if (!isLogged) {
      $("#modalLogin").modal("show");
    } else {
      window.location.href = goto;
    }
  }
</script>
<div class="naptien">
  <div class="naptien-body">

    <div class="d-inline d-sm-flex justify-content-center">
      <div class="col-md-8 mb-5 mb-sm-4">
        <div class="d-flex align-items-center justify-content-between"><a href="ranking"><small class="fw-semibold">Xem ưu đãi</small></a><small class="fw-semibold">Tích lũy: 0%</small></div>
        <div class="recharge-progress">
          <div class="progress-container">
            <div class="progress-main">
              <div class="progress-bar" style="width: 0%;"></div>
              <div class="progress-bg"></div>
            </div>
          </div>
          <div class="_3Ne69qQgMJvF7eNZAIsp_D">
            <div class="_38CkBz1hYpnEmyQwHHSmEJ">
              <div class="NusvrwidhtE2W6NagO43R">
                <div class="_1e8_XixJTleoS7HwwmyB-E">
                  <div class="_2kr5hlXQo0VVTYXPaqefA3 _2Nf9YEDFm2GHONqPnNHRWH" style="left: 1%;">
                    <div class="_12VQKhFQP9a0Wy-denB6p6">
                      <div>0</div>
                      <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                    </div>
                  </div>
                  <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 33.3333%;">
                    <div class="_12VQKhFQP9a0Wy-denB6p6">
                      <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="images/silver.png"></div>
                      <div>1Tr</div>
                    </div>
                    <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                  </div>
                  <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 66.6667%;">
                    <div class="_12VQKhFQP9a0Wy-denB6p6">
                      <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="images/gold.png"></div>
                      <div>2Tr</div>
                    </div>
                    <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                  </div>
                  <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 99%;">
                    <div class="_12VQKhFQP9a0Wy-denB6p6">
                      <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="images/diamond.png"></div>
                      <div>5Tr</div>
                    </div>
                    <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="fs-5 fw-semibold text-center">Chọn hình thức nạp</div>
      <div class="row text-center justify-content-center row-cols-2 row-cols-lg-5 g-2 g-lg-2 my-1 mb-2">
        <div class="col">
          <a class="w-100 fw-semibold" href="/recharge">
            <div class="recharge-method-item false"><img alt="method" src="/images/momo.png" data-pin-no-hover="true"></div>

          </a>
        </div>
        <div class="col">
          <a class="w-100 fw-semibold" href="naptien">
            <div class="recharge-method-item active"><img alt="method" src="/images/card.png" data-pin-no-hover="true"></div>
          </a>
        </div>
        <div class="col">
          <a class="w-100 fw-semibold" href="/atm_bank">
            <div class="recharge-method-item false"><img alt="method" src="/images/atm.png" data-pin-no-hover="true"></div>
          </a>
        </div>
      </div>
    </div>
    <div>
      <div class="overlay"></div>
      <div class="w-100 d-flex justify-content-center">
        <form action="#" id="card" class="pb-3 needs-validation" style="width: 26rem">
          <div class="fw-semibold fs-5 my-3 text-center">Nạp thẻ điện thoại</div>
          <div class="mb-2">
            <label class="fw-semibold">Nhà mạng</label>
            <select name="provider_id" id="provider_id" type="text" class="form-control form-control-solid">
              <option value="">Chọn nhà mạng</option>
              <option value="10000">Viettel</option>
              <option value="20000">Vinaphone</option>
              <option value="50000">Mobiphone</option>
            </select>
          </div>
          <div class="invalid-feedback">Không được bỏ trống</div>

          <div class="mb-2">
            <label class="fw-semibold">Mệnh giá</label>
            <select name="amount" id="amount" type="text" class="form-control form-control-solid">
              <option value="">Chọn mệnh giá</option>
              <option value="10000">10,000 - Nhận 8,000</option>
              <option value="20000">20,000 - Nhận 16,000</option>
              <option value="50000">50,000 - Nhận 40,000</option>
              <option value="100000">100,000 - Nhận 80,000</option>
              <option value="200000">200,000 - Nhận 160,000</option>
              <option value="500000">500,000 - Nhận 400,000</option>
              <option value="1000000">1,000,000 - Nhận 800,000</option>
            </select>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>

          <div class="mb-2">
            <label class="fw-semibold">Mã thẻ</label>
            <div class="input-group">
              <input id="code" name="code" type="text" autocomplete="off" placeholder="Nhập mã thẻ" class="form-control form-control-solid" value="" />
            </div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>

          <div class="mb-2">
            <label class="fw-semibold">Mã serial</label>
            <div class="input-group">
              <input id="serial" name="serial" type="text" autocomplete="off" placeholder="Nhập mã serial" class="form-control form-control-solid" value="" />
            </div>
            <div class="invalid-feedback">Không được bỏ trống</div>
          </div>

          <div class="text-center mt-3">
            <button type="submit" class="me-3 px-3 btn btn-primary">
              Xác nhận
            </button>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-row-dashed gy-5 dataTable no-footer" role="table">

          <thead>
            <tr class="text-start fw-bold text-uppercase gs-0">
              <th colspan="1" role="columnheader" class="table-sort-desc text-primary" style="cursor: pointer">#ID </th>
              <th colspan="1" role="columnheader" class="" style="cursor: default">Nhà mạng</th>
              <th colspan="1" role="columnheader" class="" style="cursor: pointer">Mệnh giá</th>
              <th colspan="1" role="columnheader" class="" style="cursor: pointer">Nhận được</th>Trạng thái</th>
              <th colspan="1" role="columnheader" class="" style="cursor: pointer">Ngày tạo</th>
            </tr>
          </thead>

          <tbody id="list-card" class="fw-semibold" role="rowgroup">
            <tr id="noContent">
              <td colspan="6">
                <div class="d-flex text-center w-100 align-content-center justify-content-center">Không có bản ghi nào</div>
              </td>
            </tr>
            <tr></tr>
          </tbody>
        </table>
      </div>

      <div class="row">
        <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"></div>
        <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
          <div>
            <ul class="pagination">
              <li class="page-item">
                <a class="page-link" style="cursor: pointer">&lt;</a>
              </li>
              <li class="page-item active">
                <a class="page-link" style="cursor: pointer">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" style="cursor: pointer">&gt;</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--
<div class="card mb-3">
			<div class="card-header">Lịch sử nạp Coin</div>
			<div class="py-3 text-center">
			<div class="table-responsive">
            	<table class="table table-bordered table-hover">
						<tr>
							<th>ID</th>
							<th>LOẠI</th>
							<th>TRẠNG THÁI</th>
							<th>MỆNH GIÁ</th>
							<th>THỜI GIAN</th>
						</tr>
					</thead>
					<tbody>
						<?php
            /*	$data = _query(_select("*","naptien","uid='$_username' ORDER BY id DESC"));
						while($row = mysqli_fetch_array($data))*/
            /*	{
								?>

								<tr>
									<td><?php echo htmlspecialchars($row['id']); ?></td>
									<td><?php echo htmlspecialchars($row['loaithe']); ?></td>
									<td><?php echo get_string_tinhtrangthe($row['tinhtrang']); ?></td>
									<td><?php echo number_format($row['sotien']);?> đ</td>
									<td><?php echo date("H:i - d/m/Y", $row['time']); ?></td>
								</tr>

							<?php }*/
            ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

                  -->

    <div class="modal fade" id="modalConfirmExchange" tabindex="-1" aria-labelledby="modalConfirmExchangeLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="my-2">
              <div class="text-center"><a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 250px;"></a></div>
            </div>
            <div class="text-center fw-semibold">
              <div id="noti" style="text-align: center;"></div>
              <div class="text-white text-center mb-2" id="waiting-times"></div>
              <div class="fs-6 mb-2">Tính năng này đang được bảo trì,</div>
              <div id="noti-active"></div>
              <span>Vui lòng chọn tính năng nạp khác. Xin cảm ơn</span>
              <a class="w-100 fw-semibold" href="/recharge">
                <div class="mt-2 aci">
                  <button type="button" onclick="handleConfirm()" class="btn-rounded btn btn-primary btn-sm">Chuyển đến momo</button>
                </div>
              </a>
              <a class="w-100 fw-semibold" href="/atm_bank">
                <div class="mt-2 aci">
                  <button type="button" onclick="handleConfirm()" class="btn-rounded btn btn-danger ">Chuyển đến Banking</button>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>









    <script>
      // Kiểm tra điều kiện hiển thị modal khi trang web được tải
      window.addEventListener("load", function() {
        var shouldShowModal = true; // Đặt điều kiện hiển thị modal ở đây

        if (shouldShowModal) {
          // Sử dụng jQuery để mở modal
          $("#modalConfirmExchange").modal("show");
        }
      });











      //////
      $.ajax({

        url: "/api/getListTelco",
        type: "GET",
        dataType: "json",

        success: function(data) {
          let itemHTML = ' <option value="">Chọn mệnh giá</option>';

          if (data.code != "01") {
            this.data = data;
            data.forEach((item, index) => {
              itemHTML += `<option value="` + item[1] + `">` + item[0] + `</option>`;
            });
            $('#provider_id').append(itemHTML);
          } else {
            $("#noContent").css('display', "block");
          }
        },
        error: function(xhr, textStatus, errorThrown) {
          $("#overlay").hide();
          console.log("Error in Operation", errorThrown);
        },
      });

      // Get list card

      let data;

      $.ajax({
        url: "/api/getlistcard",
        type: "GET",
        dataType: "json",
        data: {
          rowCount: 10
        },

        success: function(data) {
          $("#noContent").hide();
          let itemHTML = "";

          if (data.code != "01" && data.length > 0) {
            this.data = data;
            data.forEach((item, index) => {
              itemHTML += `<tr class="anhbt"><td>` + (index + 1) + `</td>`;
              itemHTML += `<td>` + item[0] + `</td>`;
              itemHTML += `<td>` + item[1] + `</td>`;
              itemHTML += `<td>` + item[2] + `</td>`;
              itemHTML +=
                `<td>` +
                (item[3] == "PENDING" ?
                  `Đang xử lý` :
                  item[3] == "SUCCESS" ?
                  "Thành công" :
                  "Lỗi") +
                `</td>`;

              itemHTML += `<td>` + item[4] + `</td></tr>`;
            });

            $("#list-card").append(itemHTML);
            $("#list-card").show();
            pageSize = 12;
            pagesCount = $(".anhbt").length;
            var totalPages = Math.ceil(pagesCount / pageSize);

            $(".pagination").twbsPagination({
              totalPages: totalPages,
              visiblePages: 5,
              prev: "&lt;",
              next: "&gt;",
              first: "",
              onPageClick: function(event, page) {
                var startIndex = pageSize * (page - 1);
                var endIndex = startIndex + pageSize;

                $(".anhbt")
                  .hide()
                  .filter(function() {
                    var idx = $(this).index();
                    return idx >= startIndex && idx < endIndex;
                  })
                  .show();
              },
            });
          } else {

            $("#noContent").css("display", "contents");
            $("#noContent").css("vertical-align", "middle");
            $("#noContent").css("text-align", "center");

          }
        },

        error: function(xhr, textStatus, errorThrown) {
          $("#overlay").hide();
          console.log("Error in Operation", errorThrown);
        },
      });

      // Submit form

      (function() {
        "use strict";
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll(".needs-validation#card");
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function(form) {
          //    $("#overlay").show();
          form.addEventListener(
            "submit",
            function(event) {
              var err = $("form#card div#error").first();

              if (err) err.remove();
              var listInput = form.querySelectorAll("input");
              var listInValid = form.querySelectorAll(".invalid-feedback");

              event.preventDefault();
              event.stopPropagation();

              let check = true;
              let provider = $("#provider_id").val();
              let amount = $("#amount").val();
              let code = $("#code").val();
              let serial = $("#serial").val();

              if (provider.trim().length == 0) {
                listInValid[0].innerHTML = "Chưa chọn nhà mạng";
                listInValid[0].classList.add("d-block");
                check = false;
              } else {
                listInValid[0].classList.remove("d-block");
              }

              if (amount.trim().length == 0) {
                listInValid[1].innerHTML = "Chưa chọn mệnh giá";
                listInValid[1].classList.add("d-block");
                check = false;
              } else {
                listInValid[1].classList.remove("d-block");
              }

              listInput.forEach((item, index) => {
                let val = item.value;

                if (val.trim().length == 0) {
                  listInValid[index + 2].innerHTML = "Không được để trống";
                  check = false;
                  listInValid[index + 2].classList.add("d-block");
                } else {
                  listInValid[index + 2].classList.remove("d-block");
                }
              });

              if (check) {
                $.ajax({
                  url: "/api/rechargePhone",
                  type: "POST",
                  dataType: "json",
                  data: JSON.stringify({
                    provider: provider,
                    amount: amount,
                    code: code,
                    serial: serial,
                  }),

                  success: function(data, textStatus, xhr) {
                    let alertNoti = null;

                    if (data.code == "00") {
                      alertNoti =
                        '<div class="alert alert-success" id="error">Nạp thẻ thành công. </br>Vui lòng đợi hệ thống xử lý.</div>';
                    } else {
                      alertNoti =
                        `<div class="alert alert-danger" id="error">` + data.text + `</div>`;
                    }
                    $("form#card").prepend(alertNoti);
                  },
                  error: function(xhr, textStatus, errorThrown) {
                    console.log("Error in Operation", errorThrown);

                  },
                });
              }
            },
            false
          );
        });
      })();
    </script>
  </div>
</div>
<?php
include_once 'end.php';
?>