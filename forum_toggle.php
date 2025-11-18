<?php
session_start();
include_once './main.php';
if (!checkAdmin($conn, $_SESSION['username'])) {
    die("Bạn không có quyền!");
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'] === 'close' ? 1 : 0;
    $conn->query("UPDATE forum_posts SET closed = $action WHERE id = $id");
}
header('Location: forum.php');
exit();
