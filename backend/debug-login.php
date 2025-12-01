<?php
require_once __DIR__ . '/database.php';

$pdo = get_db_connection();
$stmt = $pdo->prepare('SELECT id, email, password FROM users WHERE email = :email');
$stmt->execute(['email' => 'admin@example.com']);
$user = $stmt->fetch();

header('Content-Type: text/plain; charset=utf-8');
var_dump($user);
if ($user) {
    var_dump(password_verify('admin123', $user['password']));
}
