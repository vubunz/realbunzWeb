<?php
// Đảm bảo config được load trước khi dùng CONTACT_EMAIL
if (!defined('CONTACT_EMAIL')) {
    require_once __DIR__ . '/../backend/config.php';
}
?>
<!-- FOOTER -->
<footer class="mt-16 bg-[#2b0b0b] text-slate-200 pt-10 pb-6">
    <div class="container mx-auto px-4 space-y-8">
        <div class="grid md:grid-cols-3 gap-8 text-sm">
            <div>
                <h3 class="font-semibold mb-2">Tết Bính Ngọ 2026</h3>
                <p class="text-xs text-slate-400">
                    Chúc Mừng Năm Mới! Chúc bạn và gia đình một năm mới an khang,
                    thịnh vượng, tràn đầy niềm vui và may mắn.
                </p>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Liên kết nhanh</h3>
                <ul class="text-xs space-y-1 text-slate-300">
                    <li><a href="/#home" class="hover:text-amber-300">Trang chủ</a></li>
                    <li><a href="/#about" class="hover:text-amber-300">Lịch &amp; Countdown</a></li>
                    <li><a href="/blog" class="hover:text-amber-300">Blog đón Tết</a></li>
                    <li><a href="/contact" class="hover:text-amber-300">Liên hệ</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-2">Liên hệ</h3>
                <ul class="text-xs space-y-1 text-slate-300">
                    <li>Email: <span class="text-amber-300"><?= htmlspecialchars(CONTACT_EMAIL, ENT_QUOTES, 'UTF-8') ?></span></li>
                    <li>Thời gian: Thứ 2 - Thứ 6, 8:00 - 18:00</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-700 pt-3 text-center text-[11px] text-slate-500">
            © 2025 Tết Bính Ngọ 2026. Một dự án cá nhân được xây dựng bằng v0 &amp; Cursor Studio.
        </div>
    </div>
</footer>

<!-- TOAST NOTIFICATION -->
<div
    id="toast"
    class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50">
    <span id="toastMessage">Đã sao chép!</span>
</div>

<!-- MVC JAVASCRIPT MODULES -->
<script src="/js/models/countdown-model.js"></script>
<script src="/js/models/calendar-model.js"></script>
<script src="/js/models/quotes-model.js"></script>
<script src="/js/models/theme-model.js"></script>
<script src="/js/views/countdown-view.js"></script>
<script src="/js/views/calendar-view.js"></script>
<script src="/js/views/quotes-view.js"></script>
<script src="/js/views/theme-view.js"></script>
<script src="/js/controllers/countdown-controller.js"></script>
<script src="/js/controllers/calendar-controller.js"></script>
<script src="/js/controllers/quotes-controller.js"></script>
<script src="/js/controllers/theme-controller.js"></script>
<script src="/js/app.js"></script>
</body>

</html>