<?php
require_once __DIR__ . '/../backend/database.php';

$slug = $_GET['slug'] ?? '';

$CATEGORIES = [
    'y-nghia-tet' => 'Ý Nghĩa Tết Nguyên Đán',
    'mon-an-truyen-thong' => 'Món Ăn Truyền Thống',
    'trang-tri-nha-cua' => 'Trang Trí Nhà Cửa',
    'tro-choi-dan-gian' => 'Trò Chơi Dân Gian',
];

if (!isset($CATEGORIES[$slug])) {
    http_response_code(404);
    echo 'Không tìm thấy chuyên mục.';
    exit;
}

$categoryTitle = $CATEGORIES[$slug];

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("
        SELECT id, title, slug, summary, content, thumbnail, published_at
        FROM posts
        WHERE status = 'published' AND category = :category
        ORDER BY published_at DESC, id DESC
    ");
    $stmt->execute(['category' => $slug]);
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}

include __DIR__ . '/../partials/header.php';
?>

<main class="container mx-auto px-4 py-10 space-y-8">
    <section class="text-center max-w-3xl mx-auto">
        <p class="text-4xl mb-2">🎎</p>
        <h1 class="text-2xl md:text-4xl font-bold text-red-600 mb-3">
            <?= htmlspecialchars($categoryTitle, ENT_QUOTES, 'UTF-8') ?>
        </h1>
        <p class="text-sm md:text-base text-slate-600 dark:text-slate-300">
            Tổng hợp các bài viết trong chuyên mục
            <strong><?= htmlspecialchars($categoryTitle, ENT_QUOTES, 'UTF-8') ?></strong>
            giúp bạn chuẩn bị Tết trọn vẹn hơn.
        </p>
    </section>

    <section class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php if (empty($posts)): ?>
            <div class="md:col-span-2 xl:col-span-3 text-center text-slate-500 dark:text-slate-400 py-10">
                Chưa có bài viết nào trong chuyên mục này. Hãy quay lại sau nhé!
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="rounded-3xl overflow-hidden bg-amber-50/90 dark:bg-slate-900 border border-amber-100 dark:border-slate-800 shadow-sm flex flex-col">
                    <?php if (!empty($post['thumbnail'])): ?>
                        <div class="h-40 w-full overflow-hidden">
                            <img src="<?= htmlspecialchars($post['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full h-full object-cover" />
                        </div>
                    <?php endif; ?>
                    <div class="p-5 flex-1 flex flex-col">
                        <h2 class="font-semibold text-lg mb-2 text-slate-800 dark:text-slate-50">
                            <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>
                        <?php if (!empty($post['summary'])): ?>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
                                <?= htmlspecialchars($post['summary'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>
                        <div class="mt-auto pt-3 border-t border-amber-100 dark:border-slate-800 flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">
                                <?= $post['published_at'] ? date('d/m/Y', strtotime($post['published_at'])) : '—' ?>
                            </span>
                            <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400 font-medium">
                                <a href="/blog/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8') ?>">
                                    Đọc chi tiết →
                                </a>
                            </span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>