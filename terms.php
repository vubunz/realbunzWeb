<?php
ob_start();
include_once './main.php';
ob_end_flush();
?>



<style>
    /* body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        } */

    .terms-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        margin: 20px auto;
        max-width: 900px;
    }

    .terms-header {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 30px;
        border-radius: 15px 15px 0 0;
        text-align: center;
    }

    .terms-content {
        padding: 30px;
        line-height: 1.8;
    }

    .section-title {
        color: #667eea;
        border-bottom: 2px solid #667eea;
        padding-bottom: 10px;
        margin: 25px 0 15px 0;
    }

    .highlight-box {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 15px;
        margin: 15px 0;
        border-radius: 5px;
    }

    .warning-box {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 15px;
        margin: 15px 0;
    }

    .danger-box {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        padding: 15px;
        margin: 15px 0;
    }

    .back-btn {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: white;
    }

    ul {
        padding-left: 20px;
    }

    li {
        margin-bottom: 8px;
    }

    .server-name {
        color: #ff6b6b;
        font-weight: bold;
    }
</style>


<body>
    <div class="container">
        <div class="terms-container">
            <div class="terms-header">
                <h1><i class="fas fa-gavel"></i> Điều Khoản và Chính Sách</h1>
                <h3 class="server-name">Máy chủ LEGACY</h3>
                <p class="mb-0">Cập nhật lần cuối: <?= date('d/m/Y') ?></p>
            </div>

            <div class="terms-content">
                <div class="highlight-box">
                    <strong>Chào mừng bạn đến với máy chủ LEGACY!</strong><br>
                    Để đảm bảo một môi trường chơi game công bằng, lành mạnh và vui vẻ cho tất cả người chơi, chúng tôi thiết lập các điều khoản và chính sách dưới đây. Bằng việc tham gia và sử dụng dịch vụ của chúng tôi, bạn đồng ý tuân thủ tất cả các quy định này.
                </div>

                <h2 class="section-title">1. Chấp nhận Điều khoản</h2>
                <p>Bằng việc đăng ký tài khoản và/hoặc tham gia chơi game trên máy chủ của chúng tôi, bạn xác nhận rằng bạn đã đọc, hiểu và đồng ý với tất cả các điều khoản và chính sách này.</p>

                <p>Chúng tôi có quyền thay đổi, cập nhật hoặc bổ sung các điều khoản và chính sách này bất cứ lúc nào mà không cần thông báo trước. Việc bạn tiếp tục sử dụng dịch vụ sau khi có các thay đổi đồng nghĩa với việc bạn chấp nhận các thay đổi đó.</p>

                <h2 class="section-title">2. Tài khoản người chơi</h2>
                <h4>2.1. Đăng ký tài khoản</h4>
                <ul>
                    <li>Người chơi phải cung cấp thông tin chính xác khi đăng ký tài khoản</li>
                    <li>Bạn chịu trách nhiệm hoàn toàn về việc bảo mật thông tin tài khoản của mình (tên đăng nhập, mật khẩu)</li>
                    <li>Không được chia sẻ thông tin tài khoản cho bất kỳ ai</li>
                </ul>

                <h4>2.2. Trách nhiệm tài khoản</h4>
                <ul>
                    <li>Mọi hoạt động diễn ra trên tài khoản của bạn đều được coi là hành động của bạn</li>
                    <li>Chúng tôi không chịu trách nhiệm cho bất kỳ tổn thất nào phát sinh từ việc bạn không bảo mật được thông tin tài khoản</li>
                </ul>

                <h4>2.3. Xóa/Khóa tài khoản</h4>
                <ul>
                    <li>Chúng tôi có quyền khóa hoặc xóa tài khoản vĩnh viễn nếu phát hiện vi phạm các điều khoản và chính sách này</li>
                    <li>Việc xử lý có thể được thực hiện mà không cần thông báo trước hoặc bồi thường</li>
                </ul>

                <h2 class="section-title">3. Quy tắc ứng xử trong game</h2>

                <div class="danger-box">
                    <strong>Nghiêm cấm các hành vi sau:</strong>
                </div>

                <h4>3.1. Không gian lận (Hack/Cheat)</h4>
                <ul>
                    <li>Mục đích gian lận, phá hoại sự cân bằng của game</li>
                </ul>

                <h4>3.2. Không lạm dụng lỗi game (Bug Exploiting)</h4>
                <ul>
                    <li>Nghiêm cấm cố tình lợi dụng các lỗi (bug) trong game để trục lợi cá nhân</li>
                    <li>Không được gây hại cho người chơi khác thông qua lỗi game</li>
                    <li>Mọi lỗi phát hiện được khuyến khích báo cáo cho quản trị viên</li>
                </ul>

                <h4>3.3. Hành vi quấy rối/Xúc phạm</h4>
                <ul>
                    <li>Nghiêm cấm bàn luận chính trị: Nghiêm cấm mọi hình thức bàn luận, phát tán thông tin liên quan đến chính trị, tôn giáo, hoặc các vấn đề nhạy cảm khác trong game, bao gồm kênh chat, tên nhân vật, tên bang hội, hoặc bất kỳ hình thức giao tiếp nào khác.</li>
                    <li>Nghiêm cấm quấy rối, xúc phạm, đe dọa, phân biệt đối xử</li>
                    <li>Không sử dụng ngôn ngữ thô tục, khiêu dâm, kích động bạo lực</li>
                    <li>Áp dụng cho kênh chat, tên nhân vật, tên bang hội</li>
                </ul>

                <h4>3.4. Giao dịch không hợp lệ</h4>
                <ul>
                    <li>Chúng tôi sẽ không chịu trách nhiệm cho bất kỳ tranh chấp nào phát sinh từ các giao dịch không chính thức</li>
                </ul>

                <h4>3.5. Quảng cáo/Spam</h4>
                <ul>
                    <li>Nghiêm cấm quảng cáo các dịch vụ, sản phẩm không liên quan đến máy chủ</li>
                </ul>

                <h4>3.6. Giả mạo</h4>
                <ul>
                    <li>Nghiêm cấm giả mạo quản trị viên, nhân viên</li>
                    <li>Không được giả mạo bất kỳ cá nhân, tổ chức nào khác</li>
                </ul>

                <h2 class="section-title">4. Vật phẩm và Giao dịch trong game</h2>
                <h4>4.1. Quyền sở hữu</h4>
                <ul>
                    <li>Các vật phẩm, tiền tệ trong game không có giá trị quy đổi thành tiền mặt ngoài đời thực</li>
                    <li>Đây là tài sản ảo thuộc quyền sở hữu của máy chủ</li>
                </ul>

                <h4>4.2. Mất mát vật phẩm</h4>
                <ul>
                    <li>Chúng tôi không chịu trách nhiệm cho bất kỳ mất mát vật phẩm nào do lỗi của người chơi</li>
                    <li>Ví dụ: bị lừa đảo, làm rơi, xóa nhầm</li>
                    <li>Trong một số trường hợp cụ thể, chúng tôi có thể hỗ trợ nếu có bằng chứng rõ ràng</li>
                </ul>

                <h4>4.3. Đóng góp</h4>
                <ul>
                    <li>Mọi khoản đóng góp (donate) của người chơi nhằm mục đích duy trì và phát triển máy chủ</li>
                    <li>Các khoản đóng góp này là tự nguyện và không thể hoàn lại dưới bất kỳ hình thức nào</li>
                </ul>

                <h2 class="section-title">5. Chính sách bảo mật thông tin</h2>
                <h4>5.1. Thu thập thông tin</h4>
                <ul>
                    <li>Chúng tôi có thể thu thập một số thông tin cần thiết để quản lý tài khoản</li>
                    <li>Ví dụ: tên tài khoản, mật khẩu đã mã hóa, địa chỉ IP</li>
                    <li>Mục đích: duy trì dịch vụ và phát hiện vi phạm</li>
                </ul>

                <h4>5.2. Bảo vệ thông tin</h4>
                <ul>
                    <li>Chúng tôi cam kết bảo mật thông tin cá nhân của người chơi</li>
                    <li>Không tiết lộ cho bên thứ ba, trừ khi có yêu cầu từ cơ quan pháp luật có thẩm quyền</li>
                </ul>

                <h4>5.3. Dữ liệu người dùng</h4>
                <ul>
                    <li>Mặc dù chúng tôi nỗ lực bảo vệ dữ liệu, nhưng không thể đảm bảo an toàn tuyệt đối</li>
                    <li>Không thể đảm bảo trước mọi nguy cơ tấn công mạng</li>
                </ul>

                <h2 class="section-title">6. Miễn trừ trách nhiệm</h2>
                <h4>6.1. Không đảm bảo liên tục</h4>
                <ul>
                    <li>Chúng tôi không đảm bảo máy chủ sẽ hoạt động liên tục 24/7</li>
                    <li>Có thể có gián đoạn do bảo trì, sự cố kỹ thuật</li>
                    <li>Chúng tôi sẽ cố gắng thông báo trước về các đợt bảo trì lớn</li>
                </ul>

                <h4>6.2. Không chịu trách nhiệm về tổn thất</h4>
                <ul>
                    <li>Chúng tôi không chịu trách nhiệm cho bất kỳ thiệt hại trực tiếp hoặc gián tiếp nào</li>
                    <li>Thiệt hại có thể phát sinh từ việc bạn sử dụng hoặc không thể sử dụng dịch vụ</li>
                </ul>

                <h4>6.3. Lưu ý khi chơi game</h4>
                <div class="warning-box">
                    <strong>Lưu ý quan trọng:</strong> Máy chủ này là một phiên bản không chính thức của game Ninja School gốc. Bạn tự chịu trách nhiệm về mọi rủi ro pháp lý có thể phát sinh khi tham gia chơi game trên máy chủ lậu. Chúng tôi khuyến khích người chơi ủng hộ các sản phẩm chính thức.
                </div>

                <h2 class="section-title">7. Vi phạm và Xử lý</h2>
                <p>Chúng tôi có toàn quyền quyết định hình thức xử lý đối với các hành vi vi phạm điều khoản và chính sách, bao gồm nhưng không giới hạn ở:</p>
                <ul>
                    <li><strong>Cảnh cáo</strong></li>
                    <li><strong>Khóa tài khoản tạm thời</strong> (ví dụ: 1 ngày, 3 ngày, 7 ngày)</li>
                    <li><strong>Xóa vật phẩm/tiền tệ gian lận</strong></li>
                    <li><strong>Khóa tài khoản vĩnh viễn</strong></li>
                    <li><strong>Cấm địa chỉ IP</strong></li>
                </ul>

                <h2 class="section-title">8. Liên hệ</h2>
                <div class="highlight-box">
                    <strong>Mọi thắc mắc, báo cáo lỗi hoặc khiếu nại, vui lòng liên hệ với quản trị viên thông qua:</strong><br><br>
                    • Facebook: <a href="https://www.facebook.com/profile.php?id=61577114496898" target="_blank">Fanpage LEGACY</a><br>
                    • Zalo: <a href="https://zalo.me/g/wfgcta886" target="_blank">Zalo LEGACY</a><br>
                    • Email: hotro.nsolegacy@gmail.com<br>
                </div>

                <div class="text-center mt-4">
                    <p><strong>Cảm ơn bạn đã đọc và tuân thủ các điều khoản và chính sách của chúng tôi. Chúc bạn có những giây phút giải trí vui vẻ tại máy chủ LEGACY!</strong></p>
                    <a href="/" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Quay Lại Trang Chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
</body>

</html>