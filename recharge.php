<?php
// if($user == null) {
//     header("Location: /");
//     echo "<script>$('#modalLogin').modal('show');</script>";
// }
include_once 'main.php';
if (!isset($_SESSION['username'])) {
   header('Location: /');
}
?>
<div class="card">
   <div class="card-body">
      <!-- <div class="d-inline d-sm-flex justify-content-center">
         <div class="col-md-8 mb-5 mb-sm-4">
            <div class="d-flex align-items-center justify-content-between"><a href="/ranking"><small class="fw-semibold">Xem ưu đãi</small></a><small class="fw-semibold">Tích lũy: 0</small></div>
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
                                 <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="/images/rank/silver.png"></div>
                                 <div>1Tr</div>
                              </div>
                              <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                           </div>
                           <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 66.6667%;">
                              <div class="_12VQKhFQP9a0Wy-denB6p6">
                                 <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="/images/rank/gold.png"></div>
                                 <div>2Tr</div>
                              </div>
                              <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                           </div>
                           <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 99%;">
                              <div class="_12VQKhFQP9a0Wy-denB6p6">
                                 <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="/images/rank/diamond.png"></div>
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
      </div> -->

      <div class="fs-5 fw-semibold text-center">Hiện tại máy chủ chưa hỗ trợ nạp tiền</div>


      <!-- tạm tắt chức năng nạp  -->
      <!-- <div>
         <div class="fs-5 fw-semibold text-center">Chọn hình thức nạp</div>
         <div class="row text-center justify-content-center row-cols-2 row-cols-lg-5 g-2 g-lg-2 my-1 mb-2">
            <div class="col">
               <a class="w-100 fw-semibold" href="">
                  <div class="recharge-method-item "><img alt="method" src="/images/atm.png" data-pin-no-hover="true"></div>
               </a>
            </div>
         </div>
      </div>
      <?php
      include_once('./momo.php');

      ?> -->
   </div>

</div>
<?php include_once './end.php'; ?>