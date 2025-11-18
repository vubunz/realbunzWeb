<?php
define('NP', true);



$token = $configNapTien['atm']['apikey'];
$password = $configNapTien['atm']['matkhau'];
$stk = $configNapTien['atm']['sotaikhoan'];
$resultATM = file_get_contents("https://api.web2m.com/historyapiacb/$password/$stk/$token");
$result = json_decode($resultATM, true);
foreach ((array) $result['transactions'] as $data) {
  $comment        = $data['description'];
  $tranId         = $data['postingDate'];
  $amount         = $data['amount'];
  $bankName       = $data['bankName'];
  $partnerName    = $data['bankName'];
  $nameUser    = $data['receiverName'];
  if (!empty($comment)) {
    preg_match("/nt\s(\w+)/", $comment, $matches);
    if (is_array($matches) && count($matches) > 0) {
      $comment = $matches[1];
    }
    if (__query("SELECT * FROM `atm_bank` WHERE `tranid` = '$tranId' ")->num_rows == 0) {
      $username = $comment;
      $currentDate = date("Y-m-d H:i:s");
      __query("INSERT INTO `atm_bank`(`message`, `tranid`, `amount`, `bankName`, `stk` ,`username`,`created_at`,`updated_at`) VALUES ('$username','$tranId','$amount','$bankName','$nameUser','$partnerName', '$currentDate', '$currentDate');");
      $updateQuery = "UPDATE `player` SET `coin` = `coin` + '$amount', `nap` = `nap` + '$amount' WHERE `username` = '$username'";
      __query($updateQuery);
    } else {
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Loading Nạp</title>
</head>

<body>
  <script>
    setTimeout(function() {
      location.reload();
    }, 5000);
  </script>
</body>

</html>