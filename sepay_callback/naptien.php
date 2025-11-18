<?php
require_once('../CMain/connect.php'); // For $conn database connection
require_once('SepayAPI.php');

// It's better to move this to a secure config file
define('SEPAY_API_TOKEN', 'QXUXXYYKPOGIRONAFQQVIEJRZFK916RHKDCS3HHOTAGZNVIOIWM31B59CX4YEAWT');

/**
 * Extracts the first number from a string, which is assumed to be the user ID.
 * Example: "NAPTIEN 123" -> 123
 * @param string $description
 * @return string|null
 */
function get_id_from_desc($description)
{
    preg_match('/\d+/', $description, $matches);
    return $matches[0] ?? null;
}

try {
    $sepay = new SepayAPI(SEPAY_API_TOKEN);
    // Fetch recent transactions. You might want to add params here,
    // e.g., ['limit' => 50] to get more transactions.
    $response = $sepay->getTransactions();

    if (isset($response['transactions']) && is_array($response['transactions'])) {
        foreach ($response['transactions'] as $transaction) {
            // Assuming the Seepay transaction object has these keys.
            // You may need to adjust them based on the actual API response.
            $sotien = (float)($transaction['amount_in'] ?? 0);
            $cmt = $transaction['reference_number'] ?? ''; // Using reference_number as content
            $magd = $transaction['id'] ?? ''; // Seepay's transaction ID

            // We only process incoming payments with a positive amount
            if ($sotien <= 0) {
                continue;
            }

            $userId = get_id_from_desc($cmt);

            if (is_numeric($userId)) {
                // Check if user exists
                $result = $conn->query("SELECT * FROM `users` WHERE `id` = '$userId'");
                $user = $result->fetch_assoc();

                if ($user) {
                    // Check if this transaction has been processed before
                    $check_trans_res = $conn->query("SELECT * FROM `napatm` WHERE `magd` = '$magd' AND `hinhthuc` = 'SEPAY'");

                    if ($check_trans_res->num_rows == 0) {
                        // Transaction is new, process it
                        $username = $user['username'];
                        $new_money = $user['money'] + $sotien;
                        $new_total_money = $user['total_money'] + $sotien;

                        // Use a database transaction to ensure atomicity
                        $conn->begin_transaction();

                        try {
                            // 1. Update user's balance
                            $update_user = $conn->prepare("UPDATE `users` SET `money` = ?, `total_money` = ? WHERE `id` = ?");
                            $update_user->bind_param('ddi', $new_money, $new_total_money, $userId);
                            $update_user->execute();

                            // 2. Log the deposit in `napatm` table
                            // The table structure is based on the provided image.
                            $hinhthuc = 'SEPAY';
                            $thoigian = time();
                            $insert_napatm = $conn->prepare("INSERT INTO `napatm` (`username`, `hinhthuc`, `magd`, `sotien`, `thoigian`, `ndnaptien`) VALUES (?, ?, ?, ?, ?, ?)");
                            $insert_napatm->bind_param('sssids', $username, $hinhthuc, $magd, $sotien, $thoigian, $cmt);
                            $insert_napatm->execute();

                            // 3. Log the balance change in `biendongsodu`
                            // The table structure is based on the provided image.
                            $note = "Nạp " . number_format($sotien) . " vào tài khoản qua SEPAY";
                            $tongtien = $new_money;
                            $insert_biendong = $conn->prepare("INSERT INTO `biendongsodu` (`username`, `note`, `sotien`, `tongtien`, `time`) VALUES (?, ?, ?, ?, ?)");
                            $insert_biendong->bind_param('ssddi', $username, $note, $sotien, $tongtien, $thoigian);
                            $insert_biendong->execute();

                            $conn->commit();
                            echo "Success: User {$username} recharged {$sotien}. Transaction ID: {$magd}\n";

                            // Here you could trigger notifications like Pusher or Telegram if needed.
                            // e.g., send_tele("Thành viên ".$username." vừa nạp ".number_format($sotien)."...");

                        } catch (Exception $e) {
                            $conn->rollback();
                            echo "Error processing transaction {$magd}: " . $e->getMessage() . "\n";
                        }
                    } else {
                        // echo "Info: Transaction {$magd} already processed.\n";
                    }
                } else {
                    // echo "Warning: User with ID {$userId} not found for transaction {$magd}.\n";
                }
            } else {
                // echo "Info: No user ID found in reference for transaction {$magd}.\n";
            }
        }
    } else {
        echo "No transactions found or error in API response.";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "API Error: " . $e->getMessage();
}

// Close the connection
$conn->close();
