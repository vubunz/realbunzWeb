<?php
session_start();
require_once '../CMain/connect.php'; // Kết nối DB
require_once 'SepayAPI.php';
header('Content-Type: application/json');

define('SEPAY_API_TOKEN', 'QXUXXYYKPOGIRONAFQQVIEJRZFK916RHKDCS3HHOTAGZNVIOIWM31B59CX4YEAWT');

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập!'], JSON_UNESCAPED_UNICODE);
    exit;
}
$username = $_SESSION['username'];

// Validate username để tránh SQL injection
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Tên đăng nhập không hợp lệ!'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Lấy user từ DB - Sử dụng prepared statement để tránh SQL injection
$stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn!'], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('s', $username);
$stmt->execute();
$user_res = $stmt->get_result();
$user = $user_res->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy tài khoản!'], JSON_UNESCAPED_UNICODE);
    exit;
}
$userId = $user['id'];

// 3. Gọi Seepay API lấy giao dịch mới
try {
    $sepay = new SepayAPI(SEPAY_API_TOKEN);
    $response = $sepay->getTransactions(['limit' => 20]);
    if (!isset($response['transactions']) || !is_array($response['transactions'])) {
        echo json_encode(['success' => false, 'message' => 'Không lấy được giao dịch từ Seepay!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    file_put_contents('debug_sepay_transactions.txt', print_r($response['transactions'], true));

    // 4. Duyệt giao dịch, tìm giao dịch có nội dung chứa username
    $found = false;
    foreach ($response['transactions'] as $transaction) {
        $sotien = (float)($transaction['amount_in'] ?? 0);
        $cmt = $transaction['transaction_content'] ?? '';
        $magd = $transaction['id'] ?? '';
        $stk = $transaction['account_number'] ?? '';
        $bankName = $transaction['bank_name'] ?? '';
        $status = $transaction['status'] ?? 'completed';
        $created_at = $transaction['created_at'] ?? date('Y-m-d H:i:s');
        $updated_at = $transaction['updated_at'] ?? date('Y-m-d H:i:s');

        if ($sotien <= 0) continue;

        // Validate magd để tránh SQL injection
        if (empty($magd) || !preg_match('/^[a-zA-Z0-9_-]+$/', $magd)) {
            continue; // Bỏ qua giao dịch có ID không hợp lệ
        }

        // Kiểm tra nội dung chuyển khoản có chứa username (hoặc cú pháp riêng)
        if (stripos($cmt, $username) !== false) {
            // Kiểm tra đã cộng tiền chưa (dựa vào tranid) - Sử dụng prepared statement
            $check_stmt = $conn->prepare("SELECT * FROM `atm_bank` WHERE `tranid` = ?");
            if (!$check_stmt) {
                echo json_encode(['success' => false, 'message' => 'Lỗi chuẩn bị truy vấn kiểm tra!'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $check_stmt->bind_param('s', $magd);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $check_stmt->close();

            if ($check_result->num_rows == 0) {
                // Chưa cộng, tiến hành cộng tiền
                $new_coin = $user['coin'] + $sotien;
                $new_tongnap = $user['tongnap'] + $sotien;
                $conn->begin_transaction();
                try {
                    $update_user = $conn->prepare("UPDATE `users` SET `coin` = ?, `tongnap` = ? WHERE `id` = ?");
                    $update_user->bind_param('ddi', $new_coin, $new_tongnap, $userId);
                    $update_user->execute();

                    // Lưu log vào atm_bank
                    $received = 1;
                    $finish_time = time();
                    $insert_atm_bank = $conn->prepare("INSERT INTO `atm_bank` (`user_id`, `username`, `tranid`, `stk`, `message`, `amount`, `received`, `status`, `finish_time`, `created_at`, `updated_at`, `bankName`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_atm_bank->bind_param('issssdiissss', $userId, $username, $magd, $stk, $cmt, $sotien, $received, $status, $finish_time, $created_at, $updated_at, $bankName);
                    $insert_atm_bank->execute();
                    $conn->commit();
                    echo json_encode(['success' => true, 'message' => 'Nạp tiền thành công! ', 'amount' => $sotien], JSON_UNESCAPED_UNICODE);
                    $found = true;
                    break;
                } catch (Exception $e) {
                    $conn->rollback();
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi cộng tiền: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                // Đã cộng tiền rồi
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy giao dịch phù hợp! Liên hệ admin để được hỗ trợ'], JSON_UNESCAPED_UNICODE);
                $found = true;
                break;
            }
        }
    }
    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy giao dịch phù hợp. Liên hệ admin để được hỗ trợ'], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi API: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
$conn->close();
