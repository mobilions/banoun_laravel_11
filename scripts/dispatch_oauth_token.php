<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

$request = Request::create('/oauth/token', 'POST', [
    'grant_type' => 'password',
    'client_id' => getenv('PASSPORT_PASSWORD_CLIENT_ID') ?: '2',
    'client_secret' => getenv('PASSPORT_PASSWORD_CLIENT_SECRET') ?: 'tZUrnkVapkoUxSZl0FoQosjVnNy4E2zVJwofvVJP',
    'username' => 'test@example.com',
    'password' => 'secret',
    'scope' => '*',
]);

$response = $kernel->handle($request);

$status = $response->getStatusCode();
$content = $response->getContent();

echo "Status: $status\n";
$contentPreview = strlen($content) > 1000 ? substr($content, 0, 1000) . "... (truncated)" : $content;
echo "Body (first 1000 chars):\n" . $contentPreview . "\n";

$kernel->terminate($request, $response);
