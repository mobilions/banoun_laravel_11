<?php

// Deprecated legacy seeder compatibility shim.
// Use the namespaced seeder in `database/seeders/DatabaseSeeder.php` instead.

class DatabaseSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Delegate to the new namespaced seeder.
     */
    public function run(): void
    {
        if (class_exists(\Database\Seeders\DatabaseSeeder::class)) {
            (new \Database\Seeders\DatabaseSeeder())->run();
        }
    }
}
