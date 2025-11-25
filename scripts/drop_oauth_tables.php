<?php

// Drop OAuth-related tables using DB credentials from .env
$envPath = __DIR__ . '/../.env';
if (!file_exists($envPath)) {
    echo "\.env not found at {$envPath}\n";
    exit(1);
}

$env = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$vars = [];
foreach ($env as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    if (!str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    $vars[trim($k)] = trim($v);
}

$dbHost = $vars['DB_HOST'] ?? '127.0.0.1';
$dbPort = $vars['DB_PORT'] ?? 3306;
$dbName = $vars['DB_DATABASE'] ?? '';
$dbUser = $vars['DB_USERNAME'] ?? 'root';
$dbPass = $vars['DB_PASSWORD'] ?? '';

if (!$dbName) {
    echo "DB_DATABASE is not set in .env\n";
    exit(1);
}

$tables = [
    'oauth_auth_codes',
    'oauth_access_tokens',
    'oauth_refresh_tokens',
    'oauth_clients',
    'oauth_personal_access_clients',
    'personal_access_tokens'
];

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Failed to connect to DB: " . $e->getMessage() . "\n";
    exit(1);
}

foreach ($tables as $t) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS `{$t}`;");
        echo "Dropped table if existed: {$t}\n";
    } catch (Exception $e) {
        echo "Failed to drop {$t}: " . $e->getMessage() . "\n";
    }
}

echo "Done dropping OAuth tables.\n";

return 0;
