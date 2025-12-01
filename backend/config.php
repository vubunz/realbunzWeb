<?php

/**
 * Cấu hình backend đơn giản cho ứng dụng Tết 2026
 * Sử dụng cho kết nối MySQL và một số cấu hình chung.
 */

// Thông tin kết nối MySQL (chạy trên XAMPP)
const DB_HOST = '127.0.0.1';
const DB_NAME = 'realbunzWebsite';
const DB_USER = 'root';
const DB_PASS = '';

// Thiết lập timezone mặc định (nên trùng với server)
date_default_timezone_set('Asia/Ho_Chi_Minh');
