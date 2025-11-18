<?php
define('NP', true);
include_once '../../CMain/connect.php';

function __query($sql) {
    global $conn;

    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    return $result;
}
$sql = "SELECT * FROM users";
$result = __query($sql);

    $token = $configNapTien['atm']['apikey'];
    $password = $configNapTien['atm']['matkhau'];
    $stk = $configNapTien['atm']['sotaikhoan'];
    $resultATM = file_get_contents("https://api.web2m.com/historyapimbv3/$password/$stk/$token");
    $result = json_decode($resultATM, true);
	foreach ((array) $result['transactions'] as $data) {
    $comment        = $data['description'];                
    $tranId         = $data['transactionID'];                  
    $amount         = $data['amount'];
    $type           = $data['type'];
    $transactionDate = $data['transactionDate'];

    if (!empty($comment) && $type == 'IN') {
        preg_match("/\bnt\s*(\S+)/", $comment, $matches);
        if(is_array($matches) && count($matches)> 0){
            $comment = $matches[1];
           $comment =str_replace(".CT","",$comment);
        }
        $userIdQuery = "SELECT id FROM users WHERE username = '$comment'";
        $resultUserId = $conn->query($userIdQuery);
        if ($resultUserId) {
            $row = $resultUserId->fetch_assoc();
            if (__query("SELECT * FROM `atm_bank` WHERE `tranid` = '$tranId' ")->num_rows == 0) {
                $username = $comment;
                
                $currentDate = date("Y-m-d H:i:s"); 
                $bonus = 0; 
                foreach($list_recharge_price_atm as $item) {
                    if($item['amount'] == $amount) {
                        $bonus = $item['bonus'];
                        break;
                    }
                }
                $received = $amount + ($amount * $bonus / 100);

                __query("INSERT INTO `atm_bank`(`message`, `tranid`, `amount`, `received`, `created_at`, `updated_at`) VALUES ('$username','$tranId','$amount','$received', '$currentDate', '$currentDate');");
                $updateQuery = "UPDATE `users` SET `coin` = `coin` + '$received', `tongnap` = `tongnap` + '$amount' WHERE `username` = '$username'";
                __query($updateQuery);

            } else {
            }
          }
    }
	}
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loading Nap</title>
    </head>
    <body>
        <script>
            setTimeout(function () {
                location.reload();
            }, 5000);
        </script>
    </body>
    </html>
    