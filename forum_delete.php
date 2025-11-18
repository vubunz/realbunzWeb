<?php
session_start();
include_once './main.php';
if (!checkAdmin($conn, $_SESSION['username'])) {
    die("Bạn không có quyền!");
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    // Xóa bình luận trước
    $conn->query("DELETE FROM forum_comments WHERE post_id = $id");
    // Xóa bài viết
    $conn->query("DELETE FROM forum_posts WHERE id = $id");
}
header('Location: forum.php');
exit();
