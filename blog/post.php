<?php
require_once __DIR__ . '/../backend/database.php';

$categorySlug = $_GET['category'] ?? '';
$postSlug = $_GET['slug'] ?? '';

$CATEGORIES = [
    'y-nghia-tet' => 'Ý Nghĩa Tết Nguyên Đán',
    'mon-an-truyen-thong' => 'Món Ăn Truyền Thống',
    'trang-tri-nha-cua' => 'Trang Trí Nhà Cửa',
    'tro-choi-dan-gian' => 'Trò Chơi Dân Gian',
];

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("
        SELECT id, title, slug, category, summary, content, thumbnail, published_at, created_at, updated_at
        FROM posts
        WHERE slug = :slug
        LIMIT 1
    ");
    $stmt->execute(['slug' => $postSlug]);
    $post = $stmt->fetch();
} catch (PDOException $e) {
    $post = null;
}

if (!$post) {
    http_response_code(404);
    echo 'Không tìm thấy bài viết.';
    exit;
}

// Nếu category trên URL không khớp DB thì redirect về đúng URL chuẩn
if (!empty($post['category']) && $categorySlug !== $post['category']) {
    header('Location: /blog/' . urlencode($post['category']) . '/' . urlencode($post['slug']));
    exit;
}

$catSlug = $post['category'] ?? '';
$categoryName = $CATEGORIES[$catSlug] ?? 'Blog đón Tết';

// Lấy 3 bài liên quan cùng chuyên mục
$relatedPosts = [];
try {
    if ($catSlug) {
        $stmt = $pdo->prepare("
            SELECT id, title, slug, thumbnail, published_at
            FROM posts
            WHERE status = 'published'
              AND category = :category
              AND id != :id
            ORDER BY published_at DESC, id DESC
            LIMIT 3
        ");
        $stmt->execute([
            'category' => $catSlug,
            'id' => $post['id'],
        ]);
        $relatedPosts = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    $relatedPosts = [];
}

include __DIR__ . '/../partials/header.php';
?>

<main class="container mx-auto px-4 py-8 md:py-10 space-y-8">
    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-500 dark:text-slate-400 mb-2">
        <a href="/" class="hover:text-red-500">Trang chủ</a>
        <span class="mx-1">/</span>
        <a href="/blog" class="hover:text-red-500">Blog</a>
        <?php if ($catSlug): ?>
            <span class="mx-1">/</span>
            <a href="/blog/<?= htmlspecialchars($catSlug, ENT_QUOTES, 'UTF-8') ?>" class="hover:text-red-500">
                <?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </nav>

    <!-- Title + meta -->
    <header class="max-w-3xl">
        <h1 class="text-2xl md:text-3xl font-bold text-red-600 mb-3">
            <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
        </h1>
        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
            <?php if ($catSlug): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100/70 dark:bg-slate-800 text-amber-700 dark:text-amber-300">
                    <?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?>
                </span>
            <?php endif; ?>
            <span>
                <?= $post['published_at'] ? 'Đăng ' . date('d/m/Y', strtotime($post['published_at'])) : '' ?>
            </span>
        </div>
    </header>

    <!-- Thumbnail -->
    <?php if (!empty($post['thumbnail'])): ?>
        <div class="max-w-4xl">
            <img
                src="<?= htmlspecialchars($post['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
                class="w-full rounded-3xl shadow-md object-cover max-h-[400px]" />
        </div>
    <?php endif; ?>

    <!-- Content -->
    <article class="max-w-4xl bg-white/90 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 md:p-7 text-sm leading-relaxed text-slate-700 dark:text-slate-200">
        <?php if (!empty($post['summary'])): ?>
            <p class="font-medium text-slate-800 dark:text-slate-50 mb-3">
                <?= nl2br(htmlspecialchars($post['summary'], ENT_QUOTES, 'UTF-8')) ?>
            </p>
            <hr class="border-dashed border-slate-200 dark:border-slate-700 mb-4" />
        <?php endif; ?>

        <div class="prose prose-sm md:prose-base max-w-none prose-p:mb-3 prose-li:mb-1 prose-headings:text-slate-900 dark:prose-invert">
            <?php
            // Cho phép lưu content là HTML; nếu bạn chỉ nhập text thuần, nó vẫn hiển thị bình thường
            echo $post['content'];
            ?>
        </div>
    </article>

    <!-- Related posts -->
    <?php if (!empty($relatedPosts)): ?>
        <section class="max-w-4xl space-y-3">
            <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                Bài viết liên quan
            </h2>
            <div class="grid md:grid-cols-3 gap-4">
                <?php foreach ($relatedPosts as $rp): ?>
                    <article class="rounded-2xl bg-amber-50/80 dark:bg-slate-900 border border-amber-100/70 dark:border-slate-800 overflow-hidden text-xs">
                        <?php if (!empty($rp['thumbnail'])): ?>
                            <div class="h-24 w-full overflow-hidden">
                                <img
                                    src="<?= htmlspecialchars($rp['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($rp['title'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="w-full h-full object-cover" />
                            </div>
                        <?php endif; ?>
                        <div class="p-3">
                            <a
                                href="/blog/<?= htmlspecialchars($catSlug, ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars($rp['slug'], ENT_QUOTES, 'UTF-8') ?>"
                                class="font-semibold text-slate-900 dark:text-slate-50 line-clamp-2 block mb-1">
                                <?= htmlspecialchars($rp['title'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <span class="text-slate-500 dark:text-slate-400">
                                <?= $rp['published_at'] ? date('d/m/Y', strtotime($rp['published_at'])) : '—' ?>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>