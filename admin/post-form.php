<?php
session_start();
require_once __DIR__ . '/../backend/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';

$errors = [];
$isEdit = false;

// Danh sách category cố định cho blog
$CATEGORIES = [
    'y-nghia-tet' => 'Ý Nghĩa Tết Nguyên Đán',
    'mon-an-truyen-thong' => 'Món Ăn Truyền Thống',
    'trang-tri-nha-cua' => 'Trang Trí Nhà Cửa',
    'tro-choi-dan-gian' => 'Trò Chơi Dân Gian',
];

$post = [
    'id' => null,
    'title' => '',
    'slug' => '',
    'category' => 'y-nghia-tet',
    'summary' => '',
    'thumbnail' => '',
    'content' => '',
    'status' => 'draft',
    'published_at' => null,
];

try {
    $pdo = get_db_connection();
} catch (PDOException $e) {
    die('Không thể kết nối DB: ' . $e->getMessage());
}

if (isset($_GET['id'])) {
    $isEdit = true;
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => (int) $_GET['id']]);
    $post = $stmt->fetch();
    if (!$post) {
        die('Không tìm thấy bài viết.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post['title'] = trim($_POST['title'] ?? '');
    $post['slug'] = trim($_POST['slug'] ?? '');
    $post['category'] = trim($_POST['category'] ?? '');
    $post['summary'] = trim($_POST['summary'] ?? '');
    $post['thumbnail'] = trim($_POST['thumbnail'] ?? '');
    $post['content'] = trim($_POST['content'] ?? '');
    $post['status'] = $_POST['status'] ?? 'draft';
    $publishedInput = trim($_POST['published_at'] ?? '');
    $post['published_at'] = $publishedInput !== '' ? date('Y-m-d H:i:s', strtotime($publishedInput)) : null;

    if ($post['title'] === '') {
        $errors[] = 'Tiêu đề không được để trống.';
    }

    if ($post['slug'] === '') {
        $post['slug'] = $post['title'];
    }
    $post['slug'] = slugify($post['slug']);
    $post['slug'] = ensure_unique_slug($pdo, $post['slug'], $isEdit ? (int) $post['id'] : null);

    if (!in_array($post['status'], ['draft', 'published'], true)) {
        $post['status'] = 'draft';
    }

    if (!array_key_exists($post['category'], $CATEGORIES)) {
        $post['category'] = 'y-nghia-tet';
    }

    if ($post['status'] === 'published' && !$post['published_at']) {
        $post['published_at'] = date('Y-m-d H:i:s');
    }

    if (empty($errors)) {
        try {
            if ($isEdit) {
                $stmt = $pdo->prepare("
                    UPDATE posts
                    SET title = :title,
                        slug = :slug,
                        category = :category,
                        summary = :summary,
                        thumbnail = :thumbnail,
                        content = :content,
                        status = :status,
                        published_at = :published_at,
                        updated_at = NOW()
                    WHERE id = :id
                ");
                $stmt->execute([
                    'title' => $post['title'],
                    'slug' => $post['slug'],
                    'category' => $post['category'],
                    'summary' => $post['summary'],
                    'thumbnail' => $post['thumbnail'],
                    'content' => $post['content'],
                    'status' => $post['status'],
                    'published_at' => $post['published_at'],
                    'id' => $post['id'],
                ]);
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO posts (title, slug, category, summary, thumbnail, content, status, published_at, created_at, updated_at)
                    VALUES (:title, :slug, :category, :summary, :thumbnail, :content, :status, :published_at, NOW(), NOW())
                ");
                $stmt->execute([
                    'title' => $post['title'],
                    'slug' => $post['slug'],
                    'category' => $post['category'],
                    'summary' => $post['summary'],
                    'thumbnail' => $post['thumbnail'],
                    'content' => $post['content'],
                    'status' => $post['status'],
                    'published_at' => $post['published_at'],
                ]);
            }

            header('Location: posts.php?status=saved');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Không thể lưu bài viết: ' . $e->getMessage();
        }
    }
}

function datetime_for_input(?string $value): string
{
    if (!$value) {
        return '';
    }
    return date('Y-m-d\TH:i', strtotime($value));
}

function slugify(string $text): string
{
    $text = trim($text);
    if ($text === '') {
        return 'post';
    }

    // Chuẩn hoá tiếng Việt về không dấu
    $vietnameseMap = [
        // a
        'à' => 'a',
        'á' => 'a',
        'ả' => 'a',
        'ã' => 'a',
        'ạ' => 'a',
        'ă' => 'a',
        'ằ' => 'a',
        'ắ' => 'a',
        'ẳ' => 'a',
        'ẵ' => 'a',
        'ặ' => 'a',
        'â' => 'a',
        'ầ' => 'a',
        'ấ' => 'a',
        'ẩ' => 'a',
        'ẫ' => 'a',
        'ậ' => 'a',
        // A
        'À' => 'A',
        'Á' => 'A',
        'Ả' => 'A',
        'Ã' => 'A',
        'Ạ' => 'A',
        'Ă' => 'A',
        'Ằ' => 'A',
        'Ắ' => 'A',
        'Ẳ' => 'A',
        'Ẵ' => 'A',
        'Ặ' => 'A',
        'Â' => 'A',
        'Ầ' => 'A',
        'Ấ' => 'A',
        'Ẩ' => 'A',
        'Ẫ' => 'A',
        'Ậ' => 'A',
        // e
        'è' => 'e',
        'é' => 'e',
        'ẻ' => 'e',
        'ẽ' => 'e',
        'ẹ' => 'e',
        'ê' => 'e',
        'ề' => 'e',
        'ế' => 'e',
        'ể' => 'e',
        'ễ' => 'e',
        'ệ' => 'e',
        // E
        'È' => 'E',
        'É' => 'E',
        'Ẻ' => 'E',
        'Ẽ' => 'E',
        'Ẹ' => 'E',
        'Ê' => 'E',
        'Ề' => 'E',
        'Ế' => 'E',
        'Ể' => 'E',
        'Ễ' => 'E',
        'Ệ' => 'E',
        // i
        'ì' => 'i',
        'í' => 'i',
        'ỉ' => 'i',
        'ĩ' => 'i',
        'ị' => 'i',
        // I
        'Ì' => 'I',
        'Í' => 'I',
        'Ỉ' => 'I',
        'Ĩ' => 'I',
        'Ị' => 'I',
        // o
        'ò' => 'o',
        'ó' => 'o',
        'ỏ' => 'o',
        'õ' => 'o',
        'ọ' => 'o',
        'ô' => 'o',
        'ồ' => 'o',
        'ố' => 'o',
        'ổ' => 'o',
        'ỗ' => 'o',
        'ộ' => 'o',
        'ơ' => 'o',
        'ờ' => 'o',
        'ớ' => 'o',
        'ở' => 'o',
        'ỡ' => 'o',
        'ợ' => 'o',
        // O
        'Ò' => 'O',
        'Ó' => 'O',
        'Ỏ' => 'O',
        'Õ' => 'O',
        'Ọ' => 'O',
        'Ô' => 'O',
        'Ồ' => 'O',
        'Ố' => 'O',
        'Ổ' => 'O',
        'Ỗ' => 'O',
        'Ộ' => 'O',
        'Ơ' => 'O',
        'Ờ' => 'O',
        'Ớ' => 'O',
        'Ở' => 'O',
        'Ỡ' => 'O',
        'Ợ' => 'O',
        // u
        'ù' => 'u',
        'ú' => 'u',
        'ủ' => 'u',
        'ũ' => 'u',
        'ụ' => 'u',
        'ư' => 'u',
        'ừ' => 'u',
        'ứ' => 'u',
        'ử' => 'u',
        'ữ' => 'u',
        'ự' => 'u',
        // U
        'Ù' => 'U',
        'Ú' => 'U',
        'Ủ' => 'U',
        'Ũ' => 'U',
        'Ụ' => 'U',
        'Ư' => 'U',
        'Ừ' => 'U',
        'Ứ' => 'U',
        'Ử' => 'U',
        'Ữ' => 'U',
        'Ự' => 'U',
        // y
        'ỳ' => 'y',
        'ý' => 'y',
        'ỷ' => 'y',
        'ỹ' => 'y',
        'ỵ' => 'y',
        // Y
        'Ỳ' => 'Y',
        'Ý' => 'Y',
        'Ỷ' => 'Y',
        'Ỹ' => 'Y',
        'Ỵ' => 'Y',
        // d
        'đ' => 'd',
        'Đ' => 'D',
    ];
    $text = strtr($text, $vietnameseMap);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    $text = trim($text, '-');
    return $text !== '' ? $text : 'post';
}

function ensure_unique_slug(PDO $pdo, string $slug, ?int $currentId = null): string
{
    $base = $slug !== '' ? $slug : 'post';
    $candidate = $base;

    while (true) {
        $params = ['slug' => $candidate];
        $sql = 'SELECT COUNT(*) FROM posts WHERE slug = :slug';
        if ($currentId) {
            $sql .= ' AND id != :id';
            $params['id'] = $currentId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ((int) $stmt->fetchColumn() === 0) {
            return $candidate;
        }
        $candidate = $base . '-' . substr(bin2hex(random_bytes(2)), 0, 4);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $isEdit ? 'Chỉnh sửa bài viết' : 'Tạo bài viết mới' ?> | Tết 2026 Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col">
            <div class="px-5 py-4 flex items-center gap-3 border-b border-slate-800">
                <div class="w-9 h-9 rounded-xl bg-red-600/90 flex items-center justify-center shadow-md">
                    <span class="text-xl">🧧</span>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Admin Panel</p>
                    <p class="text-sm font-semibold">Tết 2026</p>
                </div>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                <a href="index" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-800 text-slate-300">
                    <span>📊</span>
                    <span>Tổng quan</span>
                </a>
                <a href="bai-viet" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800 text-slate-50 font-semibold">
                    <span>📝</span>
                    <span>Bài viết (Blog)</span>
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col">
            <header class="h-16 border-b border-slate-800 flex items-center justify-between px-6 bg-slate-950/80 backdrop-blur">
                <div>
                    <h1 class="text-base md:text-lg font-semibold"><?= $isEdit ? 'Chỉnh sửa bài viết' : 'Tạo bài viết mới' ?></h1>
                    <p class="text-xs text-slate-500 mt-0.5">Hoàn thiện nội dung blog cho trang chủ.</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="hidden sm:inline text-slate-500"><?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="bai-viet" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border border-slate-700 text-slate-200">
                        ← Quay lại danh sách
                    </a>
                </div>
            </header>

            <section class="flex-1 p-6 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
                <?php if ($errors): ?>
                    <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 text-xs text-red-200 px-4 py-3 space-y-1">
                        <?php foreach ($errors as $err): ?>
                            <p><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-400">Tiêu đề *</label>
                            <input
                                type="text"
                                name="title"
                                value="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                                required />
                        </div>
                        <div>
                            <label class="text-xs text-slate-400">Slug</label>
                            <input
                                type="text"
                                name="slug"
                                value="<?= htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                            <p class="text-[11px] text-slate-500 mt-1">Để trống hệ thống sẽ tự tạo từ tiêu đề.</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-400">Chuyên mục</label>
                            <select
                                name="category"
                                class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                <?php foreach ($CATEGORIES as $catSlug => $catLabel): ?>
                                    <option value="<?= htmlspecialchars($catSlug, ENT_QUOTES, 'UTF-8') ?>" <?= $post['category'] === $catSlug ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($catLabel, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400">Ảnh đại diện (URL)</label>
                            <input
                                type="text"
                                name="thumbnail"
                                value="<?= htmlspecialchars($post['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                                placeholder="https://example.com/anh-bai-viet.jpg" />
                            <p class="text-[11px] text-slate-500 mt-1">Sau này có thể thay bằng upload ảnh.</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-slate-400">Tóm tắt</label>
                        <textarea
                            name="summary"
                            rows="3"
                            class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"><?= htmlspecialchars($post['summary'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div>
                        <label class="text-xs text-slate-400">Nội dung</label>
                        <textarea
                            name="content"
                            rows="8"
                            class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 font-mono"><?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Có thể lưu nội dung Markdown/HTML, sau này sẽ render đúng định dạng.</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs text-slate-400">Trạng thái</label>
                            <select
                                name="status"
                                class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Nháp</option>
                                <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Đã xuất bản</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs text-slate-400">Ngày xuất bản</label>
                            <input
                                type="datetime-local"
                                name="published_at"
                                value="<?= datetime_for_input($post['published_at']) ?>"
                                class="w-full mt-1 rounded-xl border border-slate-800 bg-slate-900/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" />
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg hover:shadow-xl">
                            💾 Lưu bài viết
                        </button>
                        <a
                            href="posts.php"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-700 px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">
                            Hủy
                        </a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>

</html>