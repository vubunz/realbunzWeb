<?php
$sql_content = mysqli_query($conn, "SELECT * FROM news_posts WHERE slug = '" . $_GET["id"] . "'");
$content = [];

if ($sql_content != false && mysqli_num_rows($sql_content) > 0) {
    while ($row = mysqli_fetch_assoc($sql_content)) {
        $content = $row;
    }
}

try {
    $update_views = mysqli_query($conn, "UPDATE news_posts SET views = views + 1 WHERE slug = '" . $_GET["id"] . "'");
} catch (Exception $e) {
    // Handle the exception if needed
}

?>

<div class="d-flex align-items-center">
    <div class="post-image d-none d-sm-block">
        <img src="/images/avatar.png" alt="<?php echo $content["title"] ?>">
        <div class="post-author">Admin</div>
    </div>
    <div class="post-detail flex-fill">
        <div class="fw-bold text-primary"><?php echo $content["title"] ?></div>
        <div class="post-date"><?php echo $content["updated_at"] ?></div>
        <div class="post-content">
            <?php echo $content["content"] ?>
        </div>
        <div class="post-info mt-2"><?php echo $content["views"] ?> lượt xem, <span class="fb-comments-count" data-href="<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">0</span> bình luận</div>
    </div>
</div>
<hr class="my-3">
<div class="comment-list text-center">
    <div class="fb-comments" data-href="<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" data-width="" data-numposts="5" data-order-by="reverse_time"></div>
</div>
</div>
</div>

<?php include_once './end.php'; ?>