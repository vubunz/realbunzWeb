<?php
session_start();
require_once __DIR__ . '/../backend/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: posts.php?status=deleted');
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    header('Location: posts.php?status=deleted');
    exit;
} catch (PDOException $e) {
    header('Location: posts.php?status=error&message=' . urlencode($e->getMessage()));
    exit;
}
