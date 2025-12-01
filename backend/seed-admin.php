<?php
require_once __DIR__ . '/database.php';

$pdo = get_db_connection();
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
$pdo->prepare('UPDATE users SET password = :pass WHERE email = :email')
    ->execute([
        'pass'  => $newHash,
        'email' => 'admin@example.com',
    ]);

echo "Đã cập nhật mật khẩu admin@example.com thành admin123";
