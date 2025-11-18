<?php
include_once 'main.php'
?>


<div class="card">
                    <div class="card-body">
                        <ul class="mb-3 nav nav-tabs nav-justified" id="tabRanking" role="tablist">
                           <li class="nav-item" role="presentation"><button type="button" id="fill-tab-example-tab-1" role="tab" data-rr-ui-event-key="1" aria-controls="fill-tab-example-tabpane-1" aria-selected="false" class="nav-link active" data-bs-toggle="tab" data-bs-target="#fill-tab-example-tabpane-1">Thành viên</button></li>
                           <li class="nav-item" role="presentation"><button type="button" id="fill-tab-example-tab-2" role="tab" data-rr-ui-event-key="2" aria-controls="fill-tab-example-tabpane-2" aria-selected="false" class="nav-link" data-bs-toggle="tab" data-bs-target="#fill-tab-example-tabpane-2">VIP 1</button></li>
                           <li class="nav-item" role="presentation"><button type="button" id="fill-tab-example-tab-3" role="tab" data-rr-ui-event-key="3" aria-controls="fill-tab-example-tabpane-3" aria-selected="false" class="nav-link" data-bs-toggle="tab" data-bs-target="#fill-tab-example-tabpane-3">S_VIP</button></li>
                           <li class="nav-item" role="presentation"><button type="button" id="fill-tab-example-tab-4" role="tab" data-rr-ui-event-key="4" aria-controls="fill-tab-example-tabpane-4" aria-selected="true" class="nav-link" data-bs-toggle="tab" data-bs-target="#fill-tab-example-tabpane-4">SS_VIP</button></li>
                        </ul>
                        <!--nội dung1-->
                           <div role="tabpanel" id="fill-tab-example-tabpane-1" aria-labelledby="fill-tab-example-tab-1" class="fade tab-pane active show">
                              <div class="d-inline d-sm-flex justify-content-center">
                                 <div class="col-md-8">
                                    <div class="list-group bg-warning">
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">new</small></div>
                                          <small>Ưu đãi 100% khi nạp tiền trên 0đ.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">new</small></div>
                                          <small>Ưu đãi 102% khi nạp tiền trên 1,000,000đ.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">new</small></div>
                                          <small>Ưu đãi 103% khi nạp tiền trên 5,000,000đ.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">new</small></div>
                                          <small>Ưu đãi 105% khi nạp tiền trên 10,000,000đ.</small>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!--nội dung2-->
                           <div role="tabpanel" id="fill-tab-example-tabpane-2" aria-labelledby="fill-tab-example-tab-2" class="fade tab-pane">
                              <div class="d-inline d-sm-flex justify-content-center">
                                 <div class="col-md-8">
                                    <div class="list-group bg-warning">
                                       <span class="list-group-item list-group-item-action">
                                          <p style="text-align: center; color: red; font-weight: bold;">GIÁ: 200K</p>
                                       </span> 
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Phần thưởng đạt hạng</span><small class="fw-semibold text-danger">HOT</small></div>
                                          <small style="color: #0d6efd;" >Mặt nạ Tôn Ngộ Không vĩnh viễn, 5 vé hoàn thành nhiệm vụ.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Điểm danh VIP</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Điểm danh tại NPC VIP tại làng Tone.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Thông báo đăng nhập</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Hiển thị thông báo VIP khi đăng nhập.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Exp Đánh quái</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Tăng 20% Exp đánh quái.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Farm Lượng làng cổ</span><small class="fw-semibold text-danger">✗</small></div>
                                          <small>Có thể farm lượng tại làng cổ.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Danh hiệu VIP</span><small class="fw-semibold text-danger">✗</small></div>
                                          <small>Sở hữu danh hiệu VIP cực ngầu.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">new</small></div>
                                          <small>Hưởng nhiều ưu đãi Đặc biệt khi nạp tiền.</small>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!--nội dung3-->
                           <div role="tabpanel" id="fill-tab-example-tabpane-3" aria-labelledby="fill-tab-example-tab-3" class="fade tab-pane">
                              <div class="d-inline d-sm-flex justify-content-center">
                                 <div class="col-md-8">
                                    <div class="list-group bg-warning">
                                       <span class="list-group-item list-group-item-action">
                                          <p style="text-align: center; color: red; font-weight: bold;">GIÁ: 350K</p>
                                       </span> 
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Phần thưởng đạt hạng</span><small class="fw-semibold text-danger">HOT</small></div>
                                          <small style="color: red;">Set Doraemon vĩnh viễn, 3 rương bạch ngân, 10 vé hoàn thành nhiệm vụ</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Điểm danh VIP</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Điểm danh tại NPC VIP tại làng Tone.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Thông báo đăng nhập</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Hiển thị thông báo VIP khi đăng nhập.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Exp Đánh quái</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Tăng 20% Exp đánh quái.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Farm Lượng vùng đất bí ẩn</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Có thể farm lượng tại vùng đất bí ẩn.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Danh hiệu VIP</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Sở hữu danh hiệu VIP cực ngầu.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">new</small></div>
                                          <small>Hưởng nhiều ưu đãi Đặc biệt khi nạp tiền.</small>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                                                   <!--nội dung4-->
                           <div role="tabpanel" id="fill-tab-example-tabpane-4" aria-labelledby="fill-tab-example-tab-4" class="fade tab-pane">
                              <div class="d-inline d-sm-flex justify-content-center">
                                 <div class="col-md-8">
                                    <div class="list-group bg-warning">
                                       <span class="list-group-item list-group-item-action">
                                          <p style="text-align: center; color: red; font-weight: bold;">GIÁ: 500K</p>
                                       </span> 
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Phần thưởng đạt hạng</span><small class="fw-semibold text-danger">HOT</small></div>
                                          <small style="color: red;">HỎA KỲ LÂN 5* CÓ HỎA KÍCH, 3 rương huyền bí, 15 vé hoàn thành nhiệm vụ.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Điểm danh VIP</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Điểm danh tại NPC VIP tại làng Tone.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Thông báo đăng nhập</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Hiển thị thông báo VIP khi đăng nhập.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Exp Đánh quái</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Tăng 20% Exp đánh quái.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Farm Lượng vùng đất bí ẩn</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Có thể farm lượng tại vùng đất bí ẩn.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Danh hiệu VIP</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Sở hữu danh hiệu VIP cực ngầu.</small>
                                       </span>
                                       <span class="list-group-item list-group-item-action">
                                          <div class="d-flex w-100 justify-content-between"><span class="fw-semibold">Khuyến mãi nạp tiền</span><small class="fw-semibold text-danger">✓</small></div>
                                          <small>Hưởng nhiều ưu đãi Đặc biệt khi nạp tiền.</small>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                    </div>
                 
                    <style>
    .tab-pane {
        display: none;
    }

    .tab-pane.show {
        display: block;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get all tab buttons
    var tabButtons = document.querySelectorAll('.nav-link');

    // Get all tab panes
    var tabContents = document.querySelectorAll('.tab-pane');

    // Add click event listener to each tab button
    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            // Hide all tab panes
            tabContents.forEach(function (tabContent) {
                tabContent.classList.remove('show', 'active');
            });

            // Get the target tab pane ID
            var targetPaneId = button.getAttribute('data-bs-target');

            // Find the target tab pane element
            var targetPane = document.querySelector(targetPaneId);

            // Show the target tab pane
            targetPane.classList.add('show', 'active');
        });
    });
});

</script>
<?php
include_once './end.php';
?>
