<?php
// Trang chủ PHP sử dụng header/footer tách riêng để dễ quản lý theo MVC
require_once __DIR__ . '/backend/database.php';

// Cấu hình category cho blog
$BLOG_CATEGORIES = [
    'y-nghia-tet' => ['label' => 'Ý Nghĩa Tết', 'icon' => '🎐'],
    'mon-an-truyen-thong' => ['label' => 'Món Ăn Truyền Thống', 'icon' => '🍑'],
    'trang-tri-nha-cua' => ['label' => 'Trang Trí Nhà Cửa', 'icon' => '🌸'],
    'tro-choi-dan-gian' => ['label' => 'Trò Chơi Dân Gian', 'icon' => '🎮'],
];

$blogPostsByCategory = [];

try {
    $pdo = get_db_connection();
    foreach ($BLOG_CATEGORIES as $catSlug => $_cfg) {
        $stmt = $pdo->prepare("
            SELECT id, title, slug, summary, thumbnail, published_at
            FROM posts
            WHERE status = 'published' AND category = :category
            ORDER BY published_at DESC, id DESC
            LIMIT 4
        ");
        $stmt->execute(['category' => $catSlug]);
        $blogPostsByCategory[$catSlug] = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    // Nếu lỗi DB, để mảng rỗng và không hiển thị bài
    $blogPostsByCategory = [];
}
?>
<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ==================== MAIN CONTENT ==================== -->
<main id="home" class="container mx-auto px-4 py-8 space-y-8">
    <!-- MODULE 1: COUNTDOWN VIEW -->
    <section
        id="countdown-section"
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-red-600 via-red-500 to-amber-500 p-6 md:p-10 text-white shadow-2xl">
        <div
            class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-4 left-4 text-6xl opacity-20">🎆</div>
            <div class="absolute top-10 right-10 text-4xl opacity-20">🧧</div>
            <div class="absolute bottom-4 left-1/4 text-5xl opacity-20">🎊</div>
            <div class="absolute bottom-10 right-4 text-6xl opacity-20">🏮</div>
        </div>

        <div class="relative z-10">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-4xl font-bold mb-2">
                    🎉 Đếm ngược đến Tết Nguyên Đán
                </h2>
                <p class="text-lg md:text-xl opacity-90">
                    Tết Bính Ngọ - <span class="font-semibold">17/02/2026</span>
                </p>
            </div>

            <div
                id="countdown"
                class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 max-w-4xl mx-auto">
                <div
                    class="countdown-number bg-white/20 backdrop-blur-sm rounded-2xl p-4 md:p-6 text-center">
                    <div id="days" class="text-4xl md:text-6xl font-extrabold">
                        --
                    </div>
                    <div class="text-sm md:text-base font-medium opacity-90 mt-2">
                        Ngày
                    </div>
                </div>
                <div
                    class="countdown-number bg-white/20 backdrop-blur-sm rounded-2xl p-4 md:p-6 text-center">
                    <div id="hours" class="text-4xl md:text-6xl font-extrabold">
                        --
                    </div>
                    <div class="text-sm md:text-base font-medium opacity-90 mt-2">
                        Giờ
                    </div>
                </div>
                <div
                    class="countdown-number bg-white/20 backdrop-blur-sm rounded-2xl p-4 md:p-6 text-center">
                    <div id="minutes" class="text-4xl md:text-6xl font-extrabold">
                        --
                    </div>
                    <div class="text-sm md:text-base font-medium opacity-90 mt-2">
                        Phút
                    </div>
                </div>
                <div
                    class="countdown-number bg-white/20 backdrop-blur-sm rounded-2xl p-4 md:p-6 text-center">
                    <div id="seconds" class="text-4xl md:text-6xl font-extrabold">
                        --
                    </div>
                    <div class="text-sm md:text-base font-medium opacity-90 mt-2">
                        Giây
                    </div>
                </div>
            </div>

            <div class="mt-8 max-w-2xl mx-auto">
                <div class="flex justify-between text-sm mb-2">
                    <span>Hôm nay</span>
                    <span id="progressPercent">0%</span>
                    <span>Tết 2026</span>
                </div>
                <div class="h-3 bg-white/30 rounded-full overflow-hidden">
                    <div
                        id="progressBar"
                        class="h-full bg-white rounded-full transition-all duration-1000"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid lg:grid-cols-2 gap-8" id="about">
        <!-- MODULE 2: CALENDAR VIEW -->
        <section
            id="calendar-section"
            class="bg-white dark:bg-dark-card rounded-3xl p-6 shadow-xl border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6">
                <button
                    id="prevMonth"
                    class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                    aria-label="Tháng trước">
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div class="text-center">
                    <h3
                        id="currentMonthYear"
                        class="text-xl md:text-2xl font-bold text-red-600 dark:text-red-400"></h3>
                    <p
                        id="lunarMonthYear"
                        class="text-sm text-slate-500 dark:text-slate-400 mt-1"></p>
                </div>

                <button
                    id="nextMonth"
                    class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                    aria-label="Tháng sau">
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-sm font-semibold text-red-500 py-2">
                    CN
                </div>
                <div
                    class="text-center text-sm font-semibold text-slate-600 dark:text-slate-400 py-2">
                    T2
                </div>
                <div
                    class="text-center text-sm font-semibold text-slate-600 dark:text-slate-400 py-2">
                    T3
                </div>
                <div
                    class="text-center text-sm font-semibold text-slate-600 dark:text-slate-400 py-2">
                    T4
                </div>
                <div
                    class="text-center text-sm font-semibold text-slate-600 dark:text-slate-400 py-2">
                    T5
                </div>
                <div
                    class="text-center text-sm font-semibold text-slate-600 dark:text-slate-400 py-2">
                    T6
                </div>
                <div
                    class="text-center text-sm font-semibold text-slate-600 dark:text-slate-400 py-2">
                    T7
                </div>
            </div>

            <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>

            <div class="mt-4 text-center">
                <button
                    id="todayBtn"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-medium transition-colors">
                    📅 Hôm nay
                </button>
            </div>

            <div
                id="selectedDayInfo"
                class="mt-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-xl hidden">
                <h4 class="font-semibold text-lg mb-2" id="selectedDateTitle"></h4>
                <p
                    class="text-sm text-slate-600 dark:text-slate-400"
                    id="selectedDateLunar"></p>
                <p
                    class="text-sm text-slate-600 dark:text-slate-400"
                    id="selectedDateCanChi"></p>
            </div>
        </section>

        <!-- MODULE 3: QUOTES VIEW -->
        <section
            id="quotes-section"
            class="bg-white dark:bg-dark-card rounded-3xl p-6 shadow-xl border border-slate-200 dark:border-slate-700 flex flex-col">
            <div class="flex items-center gap-3 mb-6">
                <span class="text-3xl">💭</span>
                <h3 class="text-xl md:text-2xl font-bold">Lời hay ý đẹp</h3>
            </div>

            <div class="flex-1 flex flex-col justify-center">
                <blockquote id="quoteContainer" class="quote-fade text-center">
                    <p
                        id="quoteText"
                        class="text-xl md:text-2xl italic text-slate-700 dark:text-slate-200 leading-relaxed mb-4">
                        "Đang tải..."
                    </p>
                    <footer
                        id="quoteAuthor"
                        class="text-base text-slate-500 dark:text-slate-400 font-medium">
                        — Đang tải...
                    </footer>
                </blockquote>
            </div>

            <div
                class="flex flex-wrap justify-center gap-3 mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <button
                    id="newQuoteBtn"
                    class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-full font-medium shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Quote mới
                </button>

                <button
                    id="copyQuoteBtn"
                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-full font-medium transition-colors flex items-center gap-2">
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Sao chép
                </button>
            </div>

            <p class="text-center text-xs text-slate-400 mt-4">
                🌅 Quote của ngày sẽ tự động đổi mỗi ngày mới
            </p>
        </section>
    </div>
    <!-- BLOG SECTION - CATEGORY ROWS VỚI 3-4 BÀI GẦN NHẤT -->
    <section
        id="blog"
        class="mt-8 bg-white dark:bg-dark-card rounded-3xl p-6 md:p-8 shadow-xl border border-slate-200 dark:border-slate-700">
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl md:text-2xl font-bold flex items-center gap-2">
                    ✍️ Blog đón Tết
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Chia sẻ mẹo chuẩn bị Tết, phong tục, và kinh nghiệm lập kế hoạch năm mới.
                </p>
            </div>
            <a
                href="/blog"
                class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">
                Xem tất cả chuyên mục →
            </a>
        </div>

        <div class="space-y-8">
            <?php foreach ($BLOG_CATEGORIES as $catSlug => $cfg): ?>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl"><?= $cfg['icon'] ?></span>
                            <h4 class="font-semibold text-base md:text-lg text-slate-800 dark:text-slate-100">
                                <?= htmlspecialchars($cfg['label'], ENT_QUOTES, 'UTF-8') ?>
                            </h4>
                        </div>
                        <a
                            href="/blog/<?= htmlspecialchars($catSlug, ENT_QUOTES, 'UTF-8') ?>"
                            class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                            Xem thêm chuyên mục này →
                        </a>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <?php $posts = $blogPostsByCategory[$catSlug] ?? []; ?>
                        <?php if (empty($posts)): ?>
                            <div class="md:col-span-3 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl py-6 text-center text-xs text-slate-400">
                                Chưa có bài viết nào trong chuyên mục này.
                            </div>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <article class="rounded-2xl bg-amber-50/70 dark:bg-slate-900 border border-amber-100/80 dark:border-slate-800 overflow-hidden shadow-sm flex flex-col">
                                    <?php if (!empty($post['thumbnail'])): ?>
                                        <div class="h-32 w-full overflow-hidden">
                                            <img
                                                src="<?= htmlspecialchars($post['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
                                                class="w-full h-full object-cover" />
                                        </div>
                                    <?php endif; ?>
                                    <div class="p-4 flex-1 flex flex-col">
                                        <p class="text-[10px] uppercase tracking-wide text-slate-400 mb-1">
                                            <?= htmlspecialchars($cfg['label'], ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                        <h5 class="font-semibold text-sm mb-1 text-slate-800 dark:text-slate-50 line-clamp-2">
                                            <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                                        </h5>
                                        <?php if (!empty($post['summary'])): ?>
                                            <p class="text-[11px] text-slate-600 dark:text-slate-300 line-clamp-3 mb-2">
                                                <?= htmlspecialchars($post['summary'], ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                        <?php endif; ?>
                                        <div class="mt-auto flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-amber-100/60 dark:border-slate-800">
                                            <span>
                                                <?= $post['published_at'] ? date('d/m/Y', strtotime($post['published_at'])) : '—' ?>
                                            </span>
                                            <a
                                                href="/blog/<?= htmlspecialchars($catSlug, ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8') ?>"
                                                class="font-medium text-red-600 dark:text-red-400 hover:underline">
                                                Đọc thêm
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CONTACT SECTION (ANCHOR) -->
    <section
        id="contact"
        class="mt-8 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 p-6 text-sm text-slate-600 dark:text-slate-300">
        <h3 class="font-semibold mb-2">Liên hệ & định hướng backend</h3>
        <p>
            Phần Blog hiện tại chỉ là giao diện frontend. Khi bạn xây backend PHP,
            có thể tạo trang quản trị để thêm/sửa/xoá bài viết, sau đó render danh
            sách bài dưới dạng JSON/API hoặc server-side render.
        </p>
    </section>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>