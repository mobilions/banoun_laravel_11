<?php
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'test@example.com';
if (User::where('email', $email)->exists()) {
    echo "User {$email} already exists\n";
    exit(0);
}

$user = new User();
$user->name = 'Test User';
$user->email = $email;
$user->password = Hash::make('secret');
$user->credit_balance = 0.00;
$user->is_verified = 1;
$user->delete_status = 0;
$user->save();

echo "Created user {$email} with password 'secret'\n";
