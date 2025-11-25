<?php
$url = 'http://127.0.0.1:8000/oauth/token';
$data = [
    'grant_type' => 'password',
    'client_id' => getenv('PASSPORT_PASSWORD_CLIENT_ID') ?: '2',
    'client_secret' => getenv('PASSPORT_PASSWORD_CLIENT_SECRET') ?: 'tZUrnkVapkoUxSZl0FoQosjVnNy4E2zVJwofvVJP',
    'username' => 'test@example.com',
    'password' => 'secret',
    'scope' => '*',
];
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 10,
    ],
];
$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);
if ($result === false) {
    $err = error_get_last();
    echo "Request failed: " . ($err['message'] ?? 'unknown') . "\n";
    exit(1);
}

echo $result . "\n";
