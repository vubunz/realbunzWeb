<?php
ob_start();
include_once './main.php';

// Kiểm tra kết nối database
if (!isset($conn) || !$conn) {
    die("Lỗi kết nối database");
}

// Thiết lập phân trang
$posts_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Đếm tổng số bài viết
$count_sql = "SELECT COUNT(*) as total FROM forum_posts";
$count_result = mysqli_query($conn, $count_sql);
$total_posts = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Lấy danh sách bài viết mới nhất với tên ingame (có phân trang)
$sql = "SELECT p.*, pl.name as ingame_name, (SELECT COUNT(*) FROM forum_comments WHERE post_id = p.id) AS comment_count 
        FROM forum_posts p 
        LEFT JOIN users u ON p.author = u.username 
        LEFT JOIN players pl ON u.id = pl.id 
        ORDER BY p.created_at DESC 
        LIMIT $posts_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Kiểm tra lỗi SQL
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

ob_end_flush();
?>
<div class="container mt-4">
    <h2 class="mb-3">Bảng Tin Diễn Đàn</h2>
    <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
        <a href="forum_new.php" class="btn btn-primary mb-3">Đăng bài mới</a>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div style="display:flex; align-items:flex-start; border-bottom:1px solid #e6a96b; background:#ffcc99; padding:8px 12px;">
                <img src="/images/avatar/<?= htmlspecialchars($row['author']) ?>.png" style="width:32px;height:32px;margin-right:8px;">
                <div style="flex:1;">
                    <div style="font-weight:bold;color:#7a4b00;">
                        <a href="forum_post.php?id=<?= $row['id'] ?>" style="color:#7a4b00; text-decoration:none;">
                            <?= htmlspecialchars($row['title']) ?>
                            <?php if (strtotime($row['created_at']) >= strtotime('-3 days')): ?>
                                <img src="images/forum/new.gif" alt="new" style="vertical-align:middle; margin-left:4px;">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div style="font-size:13px;color:#6d4c1b;">
                        bởi <b><?= htmlspecialchars($row['ingame_name'] ?: $row['author']) ?></b>
                        Trả lời: <?= $row['comment_count'] ?> -
                        Xem : <?= $row['views'] ?> -
                        <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                    </div>
                </div>
                <?php if (isset($_SESSION['username']) && !empty($_SESSION['username']) && checkAdmin($conn, $_SESSION['username'])): ?>
                    <a href="forum_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa?')" class="btn btn-danger btn-sm">Xóa</a>
                    <?php if ($row['closed']): ?>
                        <a href="forum_toggle.php?id=<?= $row['id'] ?>&action=open" class="btn btn-success btn-sm">Mở</a>
                    <?php else: ?>
                        <a href="forum_toggle.php?id=<?= $row['id'] ?>&action=close" class="btn btn-warning btn-sm">Đóng</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>

        <!-- Phân trang -->
        <?php if ($total_pages > 1): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Phân trang">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Trang trước">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);

                        if ($start_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=1">1</a>
                            </li>
                            <?php if ($start_page > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($end_page < $total_pages): ?>
                            <?php if ($end_page < $total_pages - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $total_pages ?>"><?= $total_pages ?></a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Trang sau">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="text-center text-muted">
                Hiển thị <?= $offset + 1 ?>-<?= min($offset + $posts_per_page, $total_posts) ?> trong tổng số <?= $total_posts ?> bài viết
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            Chưa có bài viết nào trong diễn đàn.
        </div>
    <?php endif; ?>
    <?php include_once './end.php'; ?>

</div>