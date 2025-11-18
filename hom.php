<?php
require_once './CMain/connect.php';

echo '<div class="card-title h5">Bài viết mới</div>';
echo '<hr>';
echo '<div>';
$query_posts = "SELECT * FROM news_posts";
$posts_result = $conn->query($query_posts);

if ($posts_result->num_rows > 0) {
    while ($item = $posts_result->fetch_assoc()) {
        $item_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/post/" . $item['slug'];
        echo '
        <div class="post-item d-flex align-items-center my-2">
            <div class="post-image"><img src="/images/avatar.png" alt="' . $item['title'] . '"></div>
            <div>
                <a class="fw-bold " href="/post/' . $item['slug'] . '">' . $item['title'] . '</a>
                <div class="text-muted font-weight-bold" style="color: #198754;">Đã đăng bởi: <span class="admin-highlight">admin</span></div>
            </div>
        </div>
        ';
    }
}
?>

</div>
<div class="pt-2 card-title h5">Danh mục</div>
<hr>
<div>
    <div class="post-item d-flex align-items-center my-2">
        <div class="post-image"><img src="/images/avatar.png" alt="'.$item['title'].'"></div>
        <div>
            <a class="fw-bold false" href="giftcode">GiftCode</a>
            <div class="text-muted font-weight-bold" style="color: #198754;">Đã đăng bởi: <span class="admin-highlight">admin</span></div>
        </div>
    </div>

    <!-- <div class="post-item d-flex align-items-center my-2">
       <div class="post-image"><img src="/images/avatar.png" alt="'.$item['title'].'"></div>
         <div>
         <?php
            if (isset($_SESSION['username'])) {
                echo '<a class="fw-bold false" href="webshop">' . $configNapTien['id']['name'] . ' Store</a>';
            } else {
                echo '<a class="fw-bold false" data-bs-toggle="modal" data-bs-target="#modalLogin" href="SEXTOP">' . $configNapTien['id']['name'] . ' Store</a>';
            }
            ?>
               <div class="text-muted font-weight-bold">Shop đồ chất chuẩn dân chơi<span class="fb-comments-count" data-href="'.$item_url.'"></span></div>
         </div>
   </div> -->
    <!-- <div class="post-item d-flex align-items-center my-2">
        <div class="post-image"><img src="/images/avatar.png" alt="'.$item['title'].'"></div>
        <div>
            <a class="fw-bold false" href="item">Danh sách Item</a>
            <div class="text-muted font-weight-bold" style="color: #198754;">Đã đăng bởi: <span class="admin-highlight">admin</span></div>
        </div>
    </div> -->

    <!-- <?php
            $query_categories = "SELECT * FROM news_posts1";
            $categories_result = $conn->query($query_categories);

            if ($categories_result->num_rows > 0) {
                while ($item = $categories_result->fetch_assoc()) {
                    $item_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/category/" . $item['slug'];
                    echo '
        <div class="post-item d-flex align-items-center my-2">
            <div class="post-image"><img src="/images/avatar.png" alt="' . $item['title'] . '"></div>
            <div>
                <a class="fw-bold " href="/category/' . $item['slug'] . '">' . $item['title'] . '</a>
                <div class="text-muted font-weight-bold" style="color: #198754;">Đã đăng bởi: <span class="admin-highlight">Pince</span></div>
            </div>
        </div>
        ';
                }
            }



            ?>
<style>
    .admin-highlight {
    color: #ce0000 ;
    font-weight: bold;
    display: inline;
    font-size: 14px;
    line-height: 21px;
}
</style>
</div> -->
    <div class="mt-4">
        <div class="card-title h5">Giới thiệu</div>
        <hr>
        <div class="post-content">
            <?php
            $sql = "SELECT content FROM news_posts1";
            $result = $conn->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $decoded_content = html_entity_decode($row["content"]);
                        $formatted_content = nl2br($decoded_content);
                        echo '<div class="post-conten">' . $formatted_content . '</div>';
                    }
                } else {
                    //echo "Không có dữ liệu trong bảng gioithieu";
                }
                $result->free_result();
            } else {
                echo "Lỗi truy vấn: " . $conn->error;
            }

            //$conn->close();
            ?>
        </div>
    </div>