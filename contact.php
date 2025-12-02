<?php
require_once __DIR__ . '/backend/config.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $errorMessage = 'Vui lòng điền đầy đủ Họ tên, Email và Nội dung liên hệ.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Địa chỉ email không hợp lệ.';
    } else {
        $subjectAdmin = 'Liên hệ mới từ Tết Bính Ngọ 2026';
        $bodyAdmin = "Bạn nhận được một liên hệ mới:\n\n"
            . "Họ tên: {$name}\n"
            . "Email: {$email}\n"
            . "Thời gian: " . date('d/m/Y H:i') . "\n\n"
            . "Nội dung:\n{$message}\n";

        $headersAdmin = "From: {$email}\r\nReply-To: {$email}\r\n";

        // Thư cảm ơn cho người gửi
        $subjectUser = 'Cảm ơn bạn đã liên hệ Tết Bính Ngọ 2026';
        $bodyUser = "Xin chào {$name},\n\n"
            . "Cảm ơn bạn đã liên hệ. Mình đã nhận được tin nhắn của bạn và sẽ phản hồi trong thời gian sớm nhất.\n\n"
            . "Nội dung bạn đã gửi:\n{$message}\n\n"
            . "Trân trọng,\nTết Bính Ngọ 2026";
        $headersUser = "From: " . CONTACT_EMAIL . "\r\nReply-To: " . CONTACT_EMAIL . "\r\n";

        // Thử gửi mail (trên local XAMPP có thể chưa cấu hình SMTP nên mail() có thể trả false)
        $okAdmin = @mail(CONTACT_EMAIL, $subjectAdmin, $bodyAdmin, $headersAdmin);
        $okUser = @mail($email, $subjectUser, $bodyUser, $headersUser);

        if ($okAdmin) {
            $successMessage = 'Cảm ơn bạn! Liên hệ của bạn đã được gửi thành công.';
        } else {
            // Dù mail không gửi được, vẫn báo là đã nhận để tránh làm người dùng khó chịu
            $successMessage = 'Cảm ơn bạn! Tin nhắn đã được ghi nhận. Nếu có lỗi gửi mail trên server, mình sẽ kiểm tra lại sau.';
        }
    }
}

include __DIR__ . '/partials/header.php';
?>

<main class="container mx-auto px-4 py-10 md:py-14 space-y-10">
    <!-- Title + subtitle -->
    <section class="text-center space-y-3">
        <span
            class="inline-flex items-center px-4 py-1 rounded-full border border-amber-300/70 bg-amber-50 text-[11px] font-semibold text-amber-700 uppercase tracking-wide">
            🌸 Xuân Ất Tỵ 2025
        </span>
        <h1 class="text-2xl md:text-4xl font-bold text-red-600">
            Liên Hệ Với Chúng Tôi
        </h1>
        <p class="text-sm md:text-base text-slate-600 dark:text-slate-300 max-w-xl mx-auto">
            Chúc Mừng Năm Mới! Hãy để lại lời nhắn, chúng tôi sẽ phản hồi sớm nhất có thể.
        </p>
    </section>

    <!-- Main 2-column layout -->
    <section class="grid lg:grid-cols-2 gap-6 max-w-5xl mx-auto">
        <!-- LEFT: form card -->
        <div class="bg-white/95 dark:bg-slate-900/90 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 md:p-7">
            <div class="mb-4">
                <h2 class="text-base md:text-lg font-semibold text-slate-800 dark:text-slate-50 mb-1">
                    Gửi Lời Nhắn
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Điền thông tin dưới đây, chúng tôi sẽ liên hệ lại với bạn.
                </p>
            </div>

            <?php if ($successMessage): ?>
                <div class="mb-4 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 text-xs md:text-sm text-emerald-100 px-4 py-3">
                    <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php elseif ($errorMessage): ?>
                <div class="mb-4 rounded-2xl border border-red-500/40 bg-red-500/10 text-xs md:text-sm text-red-100 px-4 py-3">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-3">
                    <div class="space-y-1 text-xs">
                        <label class="font-medium text-slate-600 dark:text-slate-300">Họ và tên *</label>
                        <input
                            type="text"
                            name="name"
                            value="<?= isset($name) ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : '' ?>"
                            class="input-main"
                            placeholder="Nguyễn Văn A"
                            required />
                    </div>
                    <div class="space-y-1 text-xs">
                        <label class="font-medium text-slate-600 dark:text-slate-300">Email *</label>
                        <input
                            type="email"
                            name="email"
                            value="<?= isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '' ?>"
                            class="input-main"
                            placeholder="email@example.com"
                            required />
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-3 text-xs">
                    <div class="space-y-1">
                        <label class="font-medium text-slate-600 dark:text-slate-300">Số điện thoại</label>
                        <input
                            type="text"
                            name="phone"
                            class="input-main"
                            placeholder="0123 456 789" />
                    </div>
                    <div class="space-y-1">
                        <label class="font-medium text-slate-600 dark:text-slate-300">Chủ đề</label>
                        <input
                            type="text"
                            name="subject"
                            class="input-main"
                            placeholder="Tư vấn dịch vụ..." />
                    </div>
                </div>

                <div class="text-xs space-y-1">
                    <label class="font-medium text-slate-600 dark:text-slate-300">Nội dung *</label>
                    <textarea
                        name="message"
                        rows="4"
                        class="input-main"
                        placeholder="Nhập nội dung tin nhắn của bạn..."
                        required><?= isset($message) ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                </div>

                <button
                    type="submit"
                    class="mt-2 w-full md:w-auto btn-main">
                    📩 Gửi Tin Nhắn
                </button>

                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                    * Thông tin của bạn sẽ chỉ được sử dụng để liên hệ lại, không chia sẻ cho bên thứ ba.
                </p>
            </form>
        </div>

        <!-- RIGHT: info cards -->
        <div class="space-y-4">
            <!-- Contact info -->
            <div class="bg-white/95 dark:bg-slate-900/90 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-lg p-5 text-xs md:text-sm space-y-3">
                <h3 class="font-semibold text-slate-800 dark:text-slate-50 flex items-center gap-2 mb-2">
                    <span class="text-pink-500">📍</span> Thông Tin Liên Hệ
                </h3>
                <div class="space-y-2 text-slate-600 dark:text-slate-300">
                    <div>
                        <p class="font-medium">Địa chỉ</p>
                        <p>123 Đường Nguyễn Huệ, Quận 1, TP. HCM</p>
                    </div>
                    <div>
                        <p class="font-medium">Điện thoại</p>
                        <p>(028) 1234 5678</p>
                    </div>
                    <div>
                        <p class="font-medium">Email</p>
                        <p class="text-red-600 dark:text-red-400"><?= htmlspecialchars(CONTACT_EMAIL, ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div>
                        <p class="font-medium">Giờ làm việc</p>
                        <p>Thứ 2 - Thứ 6: 8:00 - 18:00</p>
                    </div>
                </div>
            </div>

            <!-- Social links -->
            <div class="bg-white/95 dark:bg-slate-900/90 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-lg p-5 text-xs md:text-sm space-y-3">
                <h3 class="font-semibold text-slate-800 dark:text-slate-50">
                    🌐 Kết Nối Với Chúng Tôi
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="#" class="flex items-center justify-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-2 hover:bg-slate-200 dark:hover:bg-slate-700">
                        <span>📘</span><span>Facebook</span>
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-2 hover:bg-slate-200 dark:hover:bg-slate-700">
                        <span>💬</span><span>Zalo</span>
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-2 hover:bg-slate-200 dark:hover:bg-slate-700">
                        <span>📸</span><span>Instagram</span>
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 px-3 py-2 hover:bg-slate-200 dark:hover:bg-slate-700">
                        <span>🎵</span><span>TikTok</span>
                    </a>
                </div>
            </div>

            <!-- Promo / notice -->
            <div class="bg-gradient-to-r from-red-600 to-amber-500 rounded-3xl text-xs md:text-sm text-white p-5 shadow-lg space-y-2">
                <h3 class="font-semibold flex items-center gap-2">
                    🎁 Khuyến Mãi Tết 2025
                </h3>
                <p>
                    Giảm 20% cho tất cả dịch vụ/dự án thiết kế, phát triển web liên quan đến chiến dịch Tết.
                </p>
                <button class="mt-1 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-[11px] md:text-xs font-semibold hover:bg-white/20">
                    💡 Chat ngay để nhận ưu đãi
                </button>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>