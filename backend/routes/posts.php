<?php
require_once __DIR__ . '/../database.php';

header('Content-Type: application/json; charset=utf-8');

// Cho phép CORS đơn giản để frontend (index.html) có thể gọi được khi cùng domain
header('Access-Control-Allow-Origin: *');

try {
    $pdo = get_db_connection();

    // Lấy tối đa 6 bài viết published, mới nhất trước
    $stmt = $pdo->prepare("
        SELECT id, title, slug, summary, published_at
        FROM posts
        WHERE status = 'published'
        ORDER BY published_at DESC, id DESC
        LIMIT 6
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data'    => $posts,
    ]);
} catch (PDOException $e) {
    // Nếu DB chưa có hoặc chưa tạo bảng, trả demo data để frontend vẫn chạy được
    http_response_code(200);
    echo json_encode([
        'success' => false,
        'error'   => 'Database not ready, using demo data: ' . $e->getMessage(),
        'data'    => [
            [
                'id'           => 1,
                'title'        => 'Checklist chuẩn bị Tết 2026',
                'slug'         => 'checklist-chuan-bi-tet-2026',
                'summary'      => 'Từ dọn nhà, sắp xếp tài chính đến chuẩn bị quà cho gia đình – demo từ PHP.',
                'published_at' => null,
            ],
            [
                'id'           => 2,
                'title'        => 'Ý nghĩa các phong tục ngày Tết',
                'slug'         => 'y-nghia-phong-tuc-tet',
                'summary'      => 'Xông đất, lì xì, mâm ngũ quả… demo từ PHP khi chưa có DB.',
                'published_at' => null,
            ],
        ],
    ]);
}
