<?php
session_start();
require_once __DIR__ . '/../backend/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';
$posts = [];
$error = '';
$flash = '';

// Danh sách category để hiển thị tên đẹp
$CATEGORIES = [
    'y-nghia-tet' => 'Ý Nghĩa Tết',
    'mon-an-truyen-thong' => 'Món Ăn',
    'trang-tri-nha-cua' => 'Trang Trí',
    'tro-choi-dan-gian' => 'Trò Chơi',
];

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'saved') {
        $flash = 'Đã lưu bài viết thành công.';
    } elseif ($_GET['status'] === 'deleted') {
        $flash = 'Đã xóa bài viết.';
    } elseif ($_GET['status'] === 'error' && isset($_GET['message'])) {
        $error = urldecode($_GET['message']);
    }
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("
        SELECT id, title, slug, category, status, published_at, updated_at
        FROM posts
        ORDER BY created_at DESC, id DESC
    ");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Không thể tải dữ liệu từ bảng posts. (' . $e->getMessage() . ')';
    // fallback demo
    $posts = [
        [
            'id' => 1,
            'title' => 'Checklist chuẩn bị Tết 2026',
            'slug' => 'checklist-chuan-bi-tet-2026',
            'status' => 'published',
            'published_at' => '2025-12-15 08:00:00',
            'updated_at' => '2025-12-10 12:00:00',
        ],
        [
            'id' => 2,
            'title' => 'Ý nghĩa các phong tục ngày Tết',
            'slug' => 'y-nghia-phong-tuc-tet',
            'status' => 'draft',
            'published_at' => null,
            'updated_at' => '2025-11-20 14:30:00',
        ],
    ];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý bài viết | Tết 2026 Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="flex min-h-screen">
        <!-- SIDEBAR -->
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
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-800 text-slate-300">
                    <span>💭</span>
                    <span>Quotes</span>
                </a>
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-800 text-slate-300">
                    <span>⏱️</span>
                    <span>Cấu hình Countdown</span>
                </a>
            </nav>
            <div class="px-4 py-3 border-t border-slate-800 text-[11px] text-slate-500">
                Sau khi CRUD hoàn tất, trang này sẽ thao tác trực tiếp với bảng <code>posts</code>.
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col">
            <header class="h-16 border-b border-slate-800 flex items-center justify-between px-6 bg-slate-950/80 backdrop-blur">
                <div>
                    <h1 class="text-base md:text-lg font-semibold">Bài viết (Blog)</h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Quản lý danh sách bài viết, trạng thái và ngày xuất bản.
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="hidden sm:inline text-slate-500">Xin chào, <?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></span>
                    <a href="post-form.php" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-slate-800 text-slate-200 text-xs">
                        <span>➕</span>
                        <span>Tạo bài viết</span>
                    </a>
                </div>
            </header>

            <section class="flex-1 p-6 space-y-4 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
                <?php if ($flash): ?>
                    <div class="rounded-xl border border-emerald-500/40 bg-emerald-500/10 text-xs text-emerald-200 px-4 py-3">
                        <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="rounded-xl border border-amber-500/40 bg-amber-500/10 text-xs text-amber-200 px-4 py-3">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 overflow-hidden">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-900/80 text-slate-400 uppercase text-xs tracking-wide">
                            <tr>
                                <th class="px-4 py-3 text-left">Tiêu đề</th>
                                <th class="px-4 py-3 text-left">Slug</th>
                                <th class="px-4 py-3 text-left">Chuyên mục</th>
                                <th class="px-4 py-3 text-left">Trạng thái</th>
                                <th class="px-4 py-3 text-left">Ngày đăng</th>
                                <th class="px-4 py-3 text-left">Cập nhật</th>
                                <th class="px-4 py-3 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <?php if (empty($posts)): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                                        Chưa có bài viết nào. Hãy bấm “Tạo bài viết”.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($posts as $post): ?>
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="px-4 py-3 font-medium text-slate-100"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-slate-400"><?= htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="px-4 py-3 text-slate-400">
                                            <?php
                                            $catKey = $post['category'] ?? '';
                                            echo isset($CATEGORIES[$catKey]) ? htmlspecialchars($CATEGORIES[$catKey], ENT_QUOTES, 'UTF-8') : '—';
                                            ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php
                                            $status = $post['status'] ?? 'draft';
                                            $badgeClass = $status === 'published' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 'bg-slate-600/20 text-slate-200 border-slate-500/40';
                                            ?>
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full border <?= $badgeClass ?>">
                                                <?= $status === 'published' ? 'Đã xuất bản' : 'Nháp' ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-400">
                                            <?= $post['published_at'] ? date('d/m/Y H:i', strtotime($post['published_at'])) : '—' ?>
                                        </td>
                                        <td class="px-4 py-3 text-slate-500">
                                            <?= $post['updated_at'] ? date('d/m/Y H:i', strtotime($post['updated_at'])) : '—' ?>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="inline-flex gap-2 text-xs">
                                                <a href="post-form.php?id=<?= (int) $post['id'] ?>" class="px-3 py-1.5 rounded-full border border-slate-700 text-slate-200 hover:bg-slate-800">Sửa</a>
                                                <a href="delete-post.php?id=<?= (int) $post['id'] ?>" class="px-3 py-1.5 rounded-full border border-red-500/60 text-red-300 hover:bg-red-500/10" onclick="return confirm('Xác nhận xóa bài này?');">Xóa</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>