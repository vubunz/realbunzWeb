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

// Email nhận liên hệ (thay bằng email thật của bạn khi deploy)
const CONTACT_EMAIL = 'hotro.realbunz@gmail.com';

// ==================== CẤU HÌNH SMTP GMAIL ====================
// Hướng dẫn tạo App Password:
// 1. Vào https://myaccount.google.com/security
// 2. Bật "2-Step Verification" nếu chưa bật
// 3. Vào "App passwords" và tạo mật khẩu mới cho "Mail"
// 4. Copy mật khẩu 16 ký tự và dán vào SMTP_PASSWORD bên dưới

// SMTP Gmail Settings
const SMTP_ENABLED = true; // Bật/tắt SMTP (false = dùng mail() function)
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587; // 587 cho TLS, 465 cho SSL
const SMTP_SECURE = 'tls'; // 'tls' hoặc 'ssl'
const SMTP_USERNAME = 'your-email@gmail.com'; // Email Gmail của bạn
const SMTP_PASSWORD = 'your-app-password'; // App Password 16 ký tự từ Google
const SMTP_FROM_EMAIL = 'your-email@gmail.com'; // Email hiển thị trong "From"
const SMTP_FROM_NAME = 'Tết Bính Ngọ 2026'; // Tên hiển thị

// Thiết lập timezone mặc định (nên trùng với server)
date_default_timezone_set('Asia/Ho_Chi_Minh');
