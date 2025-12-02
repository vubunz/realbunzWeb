<?php
session_start();
require_once __DIR__ . '/../backend/database.php';

// Nếu chưa đăng nhập thì quay về trang login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';

// Thống kê nhanh cho dashboard
$stats = [
    'posts_total' => 0,
    'posts_published' => 0,
    'posts_draft' => 0,
];
$recentPosts = [];

try {
    $pdo = get_db_connection();

    // Đếm bài viết
    $stmt = $pdo->query("
        SELECT
          COUNT(*) AS total,
          SUM(status = 'published') AS published,
          SUM(status = 'draft') AS draft
        FROM posts
    ");
    $row = $stmt->fetch();
    if ($row) {
        $stats['posts_total'] = (int) $row['total'];
        $stats['posts_published'] = (int) $row['published'];
        $stats['posts_draft'] = (int) $row['draft'];
    }

    // Lấy 5 bài mới nhất
    $stmt = $pdo->query("
        SELECT id, title, status, published_at, updated_at
        FROM posts
        ORDER BY updated_at DESC, id DESC
        LIMIT 5
    ");
    $recentPosts = $stmt->fetchAll();
} catch (PDOException $e) {
    // Nếu lỗi DB thì để stats = 0 và danh sách rỗng
    $recentPosts = [];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin | Tết 2026</title>
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
                    <p class="text-xs uppercase tracking-wide text-slate-500">
                        Admin Panel
                    </p>
                    <p class="text-sm font-semibold">Tết 2026</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                <a href="index" class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800 text-slate-50 font-medium">
                    <span>📊</span>
                    <span>Tổng quan</span>
                </a>
                <a href="bai-viet" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-800 text-slate-300">
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
                <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-800 text-slate-300">
                    <span>⚙️</span>
                    <span>Cài đặt chung</span>
                </a>
            </nav>

            <div class="px-4 py-3 border-t border-slate-800 text-[11px] text-slate-500">
                Đang là bản demo frontend. Sẽ gắn auth & CRUD PHP ở bước tiếp theo.
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col">
            <!-- TOP BAR -->
            <header class="h-16 border-b border-slate-800 flex items-center justify-between px-6 bg-slate-950/80 backdrop-blur">
                <div>
                    <h1 class="text-base md:text-lg font-semibold">Tổng quan</h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Thống kê nhanh Blog, Quotes và cấu hình Tết Bính Ngọ 2026.
                    </p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <a
                        href="../index.html"
                        class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-full border border-slate-700 text-slate-300 hover:bg-slate-800">
                        <span>🏠</span>
                        <span>Về trang chính</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <span class="hidden sm:inline text-xs text-slate-500">Đã đăng nhập:</span>
                        <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-slate-800 text-slate-200 text-xs">
                            <span>👤</span>
                            <span><?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- DASHBOARD GRID -->
            <section class="flex-1 p-6 space-y-6 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
                <!-- Stat cards -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                        <p class="text-xs text-slate-400 mb-1">Tổng bài viết</p>
                        <p class="text-2xl font-semibold"><?= $stats['posts_total'] ?></p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Dữ liệu từ bảng <code>posts</code> (nháp + đã xuất bản).
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                        <p class="text-xs text-slate-400 mb-1">Đã xuất bản</p>
                        <p class="text-2xl font-semibold text-emerald-400"><?= $stats['posts_published'] ?></p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Bài đang hiển thị ngoài trang chủ/blog.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                        <p class="text-xs text-slate-400 mb-1">Ngày Tết cấu hình</p>
                        <p class="text-2xl font-semibold">17/02/2026</p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Sau này chỉnh trong mục Cấu hình Countdown.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                        <p class="text-xs text-slate-400 mb-1">Lần cập nhật gần nhất</p>
                        <p class="text-2xl font-semibold">—</p>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Có thể lưu log trong bảng <code>audit_logs</code>.
                        </p>
                    </div>
                </div>

                <!-- Recent posts / todo -->
                <div class="grid lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-semibold">Bài viết gần đây</h2>
                            <a href="posts.php" class="text-xs text-red-400 hover:underline">Quản lý tất cả</a>
                        </div>
                        <?php if (empty($recentPosts)): ?>
                            <div class="border border-dashed border-slate-700 rounded-xl p-4 text-xs text-slate-400 text-center">
                                Chưa có bài viết nào. Hãy tạo bài đầu tiên trong mục "Bài viết (Blog)".
                            </div>
                        <?php else: ?>
                            <div class="border border-slate-800 rounded-xl text-xs overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-slate-900/80 text-slate-400 uppercase tracking-wide">
                                        <tr>
                                            <th class="px-3 py-2 text-left">Tiêu đề</th>
                                            <th class="px-3 py-2 text-left">Trạng thái</th>
                                            <th class="px-3 py-2 text-left">Cập nhật</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800">
                                        <?php foreach ($recentPosts as $post): ?>
                                            <tr class="hover:bg-slate-900/60">
                                                <td class="px-3 py-2">
                                                    <a href="post-form.php?id=<?= (int) $post['id'] ?>" class="text-slate-100 hover:text-red-300">
                                                        <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                                                    </a>
                                                </td>
                                                <td class="px-3 py-2">
                                                    <?php if ($post['status'] === 'published'): ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                                            Đã xuất bản
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-600/20 text-slate-200 border border-slate-500/40">
                                                            Nháp
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-3 py-2 text-slate-400">
                                                    <?= $post['updated_at'] ? date('d/m/Y H:i', strtotime($post['updated_at'])) : '—' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4">
                        <h2 class="text-sm font-semibold mb-2">Ghi chú triển khai backend</h2>
                        <ul class="text-xs text-slate-400 space-y-1 list-disc list-inside">
                            <li>Thêm auth (login.php → xử lý session, kiểm tra ở index.php).</li>
                            <li>Tạo controller CRUD cho <code>posts</code> (create/edit/delete).</li>
                            <li>Tạo bảng <code>quotes</code> & giao diện quản lý.</li>
                            <li>Form chỉnh ngày Tết & cấu hình countdown.</li>
                        </ul>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>