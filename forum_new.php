<?php
include_once './main.php';
if (!isset($_SESSION['username'])) {
    header('Location: forum.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = $_SESSION['username'];

    // Kiểm tra độ dài
    if (strlen($title) > 180) {
        $error = 'Tiêu đề không được vượt quá 180 ký tự.';
    } elseif (strlen($content) > 300) {
        $error = 'Nội dung không được vượt quá 300 ký tự.';
    } elseif ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO forum_posts (author, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $author, $title, $content);
        $stmt->execute();
        header('Location: forum.php');
        exit();
    } else {
        $error = 'Vui lòng nhập đầy đủ tiêu đề và nội dung.';
    }
}
?>
<div class="container mt-4">
    <h2>Đăng bài mới</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề </label>
            <input type="text" class="form-control" id="title" name="title" maxlength="180" required>
            <div class="form-text">
                <span id="titleCount">0</span>/180 ký tự
            </div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Nội dung </label>
            <textarea class="form-control" id="content" name="content" rows="5" maxlength="300" required></textarea>
            <div class="form-text">
                <span id="contentCount">0</span>/300 ký tự
            </div>
        </div>
        <button type="submit" class="btn btn-success">Đăng bài</button>
        <a href="forum.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<script>
    // Đếm ký tự cho tiêu đề
    document.getElementById('title').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('titleCount').textContent = count;

        if (count > 180) {
            document.getElementById('titleCount').style.color = 'red';
        } else {
            document.getElementById('titleCount').style.color = '';
        }
    });

    // Đếm ký tự cho nội dung
    document.getElementById('content').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('contentCount').textContent = count;

        if (count > 300) {
            document.getElementById('contentCount').style.color = 'red';
        } else {
            document.getElementById('contentCount').style.color = '';
        }
    });
</script>