<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo 'Bạn cần đăng nhập!';
    exit;
}

$username = $_SESSION['username'];
$target_dir = "images/avatar/";
$target_file = $target_dir . $username . ".png"; // Luôn lưu thành .png

$maxFileSize = 2 * 1024 * 1024; // 2MB

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    if ($_FILES['avatar']['size'] > $maxFileSize) {
        echo "File quá lớn! Vui lòng chọn file nhỏ hơn 2MB.";
        exit;
    }

    $check = getimagesize($_FILES['avatar']['tmp_name']);
    if ($check === false) {
        echo "File không phải là ảnh!";
        exit;
    }

    // Chỉ cho phép PNG, JPG, JPEG, GIF
    $allowed = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
    if (!in_array($check['mime'], $allowed)) {
        echo "Chỉ cho phép file PNG, JPG, JPEG, GIF!";
        exit;
    }

    // Chuyển ảnh sang PNG nếu không phải PNG
    $tmp_name = $_FILES['avatar']['tmp_name'];
    $image = null;
    if ($check['mime'] == 'image/png') {
        $image = @imagecreatefrompng($tmp_name);
    } elseif ($check['mime'] == 'image/jpeg' || $check['mime'] == 'image/jpg') {
        $image = imagecreatefromjpeg($tmp_name);
    } elseif ($check['mime'] == 'image/gif') {
        $image = imagecreatefromgif($tmp_name);
    }

    if ($image) {
        // Resize về kích thước chuẩn nếu muốn (ví dụ 128x128)
        $width = imagesx($image);
        $height = imagesy($image);
        $new_width = 128;
        $new_height = 128;
        $resized = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Lưu thành PNG
        imagepng($resized, $target_file);
        imagedestroy($image);
        imagedestroy($resized);

        echo "<span style='color:green'>Cập nhật avatar thành công!</span>";
    } else {
        echo "Lỗi xử lý ảnh!";
    }
} else {
    echo "Không nhận được file!";
}
