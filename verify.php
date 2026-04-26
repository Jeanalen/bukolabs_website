<?php
$secret = "0x4AAAAAABlNKdDEqAn_1X5Sw_sNcmfYgKk";

$response = $_POST['cf-turnstile-response'] ?? '';

if (empty($response)) {
    echo "No CAPTCHA token received.";
    exit;
}

$url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";

$data = [
    'secret' => $secret,
    'response' => $response,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$verify = file_get_contents($url, false, $context);
$result = json_decode($verify);

if ($result && $result->success) {
    echo "<p style='color:green;'>✅ Turnstile Verification Passed!</p>";
} else {
    echo "<p style='color:red;'>❌ CAPTCHA Failed.</p>";
}
?>