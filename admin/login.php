<?php
session_start();
require_once __DIR__ . '/../backend/database.php';

$error = '';

// Nếu đã đăng nhập thì chuyển thẳng vào dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
    } else {
        try {
            $pdo = get_db_connection();
            $stmt = $pdo->prepare('SELECT id, email, password, name FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id']   = $user['id'];
                $_SESSION['admin_name'] = $user['name'] ?: $user['email'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
            }
        } catch (PDOException $e) {
            $error = 'Lỗi kết nối database. Hãy kiểm tra cấu hình và tạo bảng users. (' . $e->getMessage() . ')';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập Admin | Tết 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 text-slate-100 flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-red-600/90 shadow-lg mb-3">
                <span class="text-2xl">🧧</span>
            </div>
            <h1 class="text-2xl font-bold mb-1">Tết 2026 Admin</h1>
            <p class="text-sm text-slate-400">
                Đăng nhập để quản lý Blog, Quotes và cấu hình Countdown.
            </p>
        </div>

        <?php if ($error): ?>
            <div class="mb-3 rounded-xl border border-red-500/60 bg-red-500/10 text-xs text-red-200 px-4 py-2">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-4 bg-slate-900/60 border border-slate-800 rounded-2xl p-6 shadow-2xl backdrop-blur">
            <div class="space-y-1">
                <label for="email" class="text-sm font-medium text-slate-300">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="admin@example.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
            </div>

            <div class="space-y-1">
                <label for="password" class="text-sm font-medium text-slate-300">Mật khẩu</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="••••••••" />
            </div>

            <button
                type="submit"
                class="w-full mt-2 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-red-500 to-amber-500 px-3 py-2.5 text-sm font-semibold text-white shadow-lg hover:shadow-xl hover:from-red-600 hover:to-amber-600 transition">
                Đăng nhập
            </button>

            <p class="text-[11px] text-center text-slate-500 mt-2">
                Gợi ý: tạo bảng <code>users</code> và dùng <code>password_hash()</code> để lưu mật khẩu an toàn.
            </p>
        </form>
    </div>
</body>

</html>