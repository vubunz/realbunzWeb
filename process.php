<?php
// Kiểm tra xác nhận CAPTCHA
$recaptcha_secret = "6LfsiaooAAAAAAT_nG7t8CHm7a5ho0LafXIY4IcJ";
$recaptcha_response = $_POST['g-recaptcha-response'];
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => $recaptcha_secret,
    'response' => $recaptcha_response
);

$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
$result = json_decode($result, true);

// Trả kết quả xác nhận dưới dạng JSON
header('Content-Type: application/json');

if ($result['success']) {
    // Nếu xác nhận thành công, trả về JSON success
    echo json_encode(array('success' => true));
} else {
    // Nếu xác nhận thất bại, trả về JSON failure
    echo json_encode(array('success' => false));
}
?>
