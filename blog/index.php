<?php
include __DIR__ . '/../partials/header.php';
?>

<main class="container mx-auto px-4 py-10 space-y-8">
    <section class="text-center max-w-3xl mx-auto">
        <p class="text-4xl mb-2">🏮</p>
        <h1 class="text-2xl md:text-4xl font-bold text-red-600 mb-3">
            Văn Hóa và Truyền Thống Tết
        </h1>
        <p class="text-sm md:text-base text-slate-600 dark:text-slate-300">
            Khám phá ý nghĩa Tết, món ăn truyền thống, cách trang trí nhà cửa và những trò chơi dân gian
            giúp không khí Tết thêm rộn ràng.
        </p>
    </section>

    <section class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
        <article class="rounded-3xl bg-white dark:bg-dark-card border border-slate-200 dark:border-slate-800 p-6 text-center shadow-sm">
            <div class="text-4xl mb-3">🎐</div>
            <h2 class="font-semibold text-lg mb-2 text-red-600">Ý Nghĩa Tết Nguyên Đán</h2>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
                Cội nguồn, phong tục, và những câu chuyện đằng sau ngày Tết cổ truyền của người Việt.
            </p>
            <a href="/blog/y-nghia-tet" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                Đọc thêm về ý nghĩa Tết →
            </a>
        </article>

        <article class="rounded-3xl bg-white dark:bg-dark-card border border-slate-200 dark:border-slate-800 p-6 text-center shadow-sm">
            <div class="text-4xl mb-3">🍑</div>
            <h2 class="font-semibold text-lg mb-2 text-red-600">Món Ăn Truyền Thống</h2>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
                Bánh chưng, bánh tét, thịt kho tàu, nem rán… và những món ăn mang đậm hương vị ngày Tết.
            </p>
            <a href="/blog/mon-an-truyen-thong" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                Khám phá thêm món Tết →
            </a>
        </article>

        <article class="rounded-3xl bg-white dark:bg-dark-card border border-slate-200 dark:border-slate-800 p-6 text-center shadow-sm">
            <div class="text-4xl mb-3">🌸</div>
            <h2 class="font-semibold text-lg mb-2 text-red-600">Trang Trí Nhà Cửa</h2>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
                Hoa đào, hoa mai, câu đối đỏ, lồng đèn… giúp không gian sống rực rỡ và nhiều may mắn.
            </p>
            <a href="/blog/trang-tri-nha-cua" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                Xem gợi ý trang trí →
            </a>
        </article>

        <article class="rounded-3xl bg-white dark:bg-dark-card border border-slate-200 dark:border-slate-800 p-6 text-center shadow-sm">
            <div class="text-4xl mb-3">🎮</div>
            <h2 class="font-semibold text-lg mb-2 text-red-600">Trò Chơi Dân Gian</h2>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
                Bầu cua tôm cá, cờ tướng, ô ăn quan… những hoạt động gắn kết cả gia đình trong những ngày Tết.
            </p>
            <a href="/blog/tro-choi-dan-gian" class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                Chơi gì dịp Tết? →
            </a>
        </article>
    </section>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>