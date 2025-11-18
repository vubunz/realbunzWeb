<?php
    if($user == null) {
        header("Location: /");
        echo "<script>$('#modalLogin').modal('show');</script>";
    }
?>
<style>
   @-webkit-keyframes my {
	 0% { color: #0a338b; } 
	 50% { color: #cf1616;  } 
	 100% { color: #8f770f;  } 
 }
 @-moz-keyframes my { 
	 0% { color: #63530c;  } 
	 50% { color: #0f4c92;  }
	 100% { color: #139433;  } 
 }
 @-o-keyframes my { 
	 0% { color: #0a338b; } 
	 50% { color: #cf1616;  } 
	 100% { color: #07aa38;  } 
 }
 @keyframes my { 
	 0% { color: #0a338b; } 
	 50% { color: #1bda09;  } 
	 100% { color: #c71a0d;  } 
 } 
 .txt-trans {
         font-size:16px;
         font-weight:bold;
         -webkit-animation: my 700ms infinite;
         -moz-animation: my 700ms infinite; 
         -o-animation: my 700ms infinite; 
         animation: my 700ms infinite;
}
</style>
<div class="d-inline d-sm-flex justify-content-center">
   <div class="col-md-8 mb-5 mb-sm-4">
      <div class="recharge-progress">
         <div class="progress-container">
            <div class="progress-main">
               <div class="progress-bar" id="process-charge"></div>
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
                     <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 20%;">
                        <div class="_12VQKhFQP9a0Wy-denB6p6">
                           <div class="_3KQP4x4OyaOj6NIpgE7cKm"><img alt="" class="_2KchEf_H4jouWwDFDPi5hm" src="/images/rank/silver.png"></div>
                           <div>1Tr</div>
                        </div>
                        <div class="_3toQ_1IrcIyWvRGrIm2fHJ"></div>
                     </div>
                     <div class="_2kr5hlXQo0VVTYXPaqefA3" style="left: 40%;">
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
</div>
<div>
   <div class="fs-5 fw-semibold text-center">Chọn hình thức nạp</div>
   <div class="row text-center justify-content-center row-cols-2 row-cols-lg-5 g-2 g-lg-2 my-1 mb-2">
      <div class="col">
         <a class="w-100 fw-semibold" href="/?page=recharge&tab=momo">
            <div class="recharge-method-item <?php echo ($tab!="atm") ? "active" : "false"; ?>"><img alt="method" src="/images/momo.png" data-pin-no-hover="true"></div>
         </a>
      </div>
      <div class="col">
         <a class="w-100 fw-semibold" href="/?page=recharge&tab=atm">
            <div class="recharge-method-item <?php echo ($tab=="atm") ? "active" : "false"; ?>"><img alt="method" src="/images/mb.png" data-pin-no-hover="true" class="nganhang"></div>
         </a>
      </div>
   </div>
</div>
<?php
    if ($tab != "atm") include_once('recharge/momo.php');
    else if($tab == "atm") include_once('recharge/atm.php');
?>
<script>
   let tongnap = <?php echo ($user['tongnap'] == '' ? 0 : $user['tongnap']) ?>;
   $('#process-charge').attr("style",`width: `+ <?php echo ($user['tongnap']/5000000)*100 ?> +`%`);
   $('#tich-luy').html(`Tích luỹ : `+ tongnap.toLocaleString('it-IT', {style : 'currency', currency : 'VND'}) +``);
</script>

