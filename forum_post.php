<?php
include_once './main.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: forum.php');
    exit();
}
$post_id = intval($_GET['id']);
// Tăng lượt xem
$conn->query("UPDATE forum_posts SET views = views + 1 WHERE id = $post_id");
// Lấy thông tin bài viết với tên ingame
$sql = "SELECT p.*, pl.name as ingame_name 
        FROM forum_posts p 
        LEFT JOIN users u ON p.author = u.username 
        LEFT JOIN players pl ON u.id = pl.id 
        WHERE p.id = $post_id";
$result = mysqli_query($conn, $sql);
$post = mysqli_fetch_assoc($result);
if (!$post) {
    echo '<div class="alert alert-danger">Bài viết không tồn tại!</div>';
    exit();
}
// Xử lý gửi bình luận
if (isset($_SESSION['username']) && isset($_POST['comment'])) {
    if ($post['closed']) {
        echo '<div class="alert alert-warning mt-3">Bài viết đã bị đóng, không thể bình luận.</div>';
        exit();
    }
    $content = trim($_POST['comment']);
    $author = $_SESSION['username'];
    if ($content) {
        $stmt = $conn->prepare("INSERT INTO forum_comments (post_id, author, content) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $post_id, $author, $content);
        $stmt->execute();
        header('Location: forum_post.php?id=' . $post_id);
        exit();
    }
}
// Lấy bình luận với tên ingame
$sql = "SELECT c.*, pl.name as ingame_name 
        FROM forum_comments c 
        LEFT JOIN users u ON c.author = u.username 
        LEFT JOIN players pl ON u.id = pl.id 
        WHERE c.post_id = $post_id 
        ORDER BY c.created_at ASC";
$comments = mysqli_query($conn, $sql);
?>
<div class="container mt-4">
    <a href="forum.php" class="btn btn-secondary mb-3">&laquo; Quay lại</a>
    <div class="card mb-3">
        <div class="card-header bg-warning" style="display: flex; align-items: center;">
            <img src="/images/avatar/<?= htmlspecialchars($post['author']) ?>.png" style="width:32px;height:32px;margin-right:8px; border-radius:50%;">
            <div style="flex:1;">
                <strong><?= htmlspecialchars($post['title']) ?></strong>
                <?php if (strtotime($post['created_at']) >= strtotime('-3 days')): ?>
                    <img src="images/forum/new.gif" alt="new" style="vertical-align:middle; margin-left:4px;">
                <?php endif; ?>
                <span class="float-end">
                    bởi <?= htmlspecialchars($post['ingame_name'] ?: $post['author']) ?> | <?= $post['created_at'] ?> | Lượt xem: <?= $post['views'] + 1 ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        </div>
    </div>
    <h5>Bình luận</h5>
    <?php while ($c = mysqli_fetch_assoc($comments)): ?>
        <div class="card mb-2">
            <div class="card-body" style="display: flex; align-items: flex-start;">
                <img src="/images/avatar/<?= htmlspecialchars($c['author']) ?>.png" style="width:28px;height:28px;margin-right:8px; border-radius:50%;">
                <div>
                    <strong><?= htmlspecialchars($c['ingame_name'] ?: $c['author']) ?></strong>
                    <span class="text-muted">(<?= $c['created_at'] ?>)</span><br>
                    <?= nl2br(htmlspecialchars($c['content'])) ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    <?php if ($post['closed']): ?>
        <div class="alert alert-warning mt-3">Bài viết đã bị đóng, không thể bình luận.</div>
    <?php elseif (isset($_SESSION['username'])): ?>
        <form method="post" class="mt-3">
            <div class="mb-3">
                <label for="comment" class="form-label">Bình luận của bạn</label>
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info mt-3">Vui lòng <a href="#" data-bs-toggle="modal" data-bs-target="#modalLogin">đăng nhập</a> để bình luận.</div>
    <?php endif; ?>
</div>