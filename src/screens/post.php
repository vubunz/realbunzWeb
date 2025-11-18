<?php
require_once './CMain/connect.php';

$sql_content = "SELECT * FROM news_posts WHERE slug = '" . $_GET["id"] . "'";
$result_content = mysqli_query($conn, $sql_content);

$content = [];
if ($result_content !== false && mysqli_num_rows($result_content) > 0) {
    while ($row = mysqli_fetch_assoc($result_content)) {
        $content = $row;
    }
}

try {
    $update_views_sql = "UPDATE news_posts SET views = views + 1 WHERE slug = '" . $_GET["id"] . "'";
    mysqli_query($conn, $update_views_sql);
} catch (Exception $e) {
}

mysqli_close($conn);
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
