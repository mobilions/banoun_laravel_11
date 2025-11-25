Upgrade & Configuration Notes — November 21, 2025

Summary
- Modernized repo for Laravel 11 compatibility and re-applied custom config/env placeholders.
- Added environment placeholders for Passport, queue settings, and trusted proxies.
- Updated `TrustProxies` middleware to read trusted proxies/headers from env at runtime.
- Repaired failing tests and updated `tests/Feature/ExampleTest.php` to match the app's auth behavior.
- Built front-end assets with Vite; build succeeds (deprecation warnings from Sass).

Files changed (high level)
- `app/Providers/RouteServiceProvider.php` — replaced earlier to follow Laravel 11 style. (previous work)
- `database/factories/UserFactory.php` — converted to class-based factory. (previous work)
- `database/seeders/DatabaseSeeder.php` — namespaced seeder added + compatibility shim. (previous work)
- `config/passport.php` — added vendor defaults to `config/` (previous work).
- `app/Http/Middleware/TrustProxies.php` — updated to read `TRUSTED_PROXIES` and `TRUSTED_PROXY_HEADERS` from `.env`.
- `.env` — appended placeholders: `PASSPORT_*`, `DB_QUEUE_*`, `TRUSTED_PROXIES`, `TRUSTED_PROXY_HEADERS`.
- `tests/Feature/ExampleTest.php` — assertion changed to expect `302` because `/` requires auth.

Commands run here (results)
- `composer dump-autoload` — completed (optimized autoload regenerated).
- `php artisan migrate:status` — inspected earlier; some OAuth migrations had been pending and required idempotency fixes.
- `php artisan test` — all tests passed after adjusting the feature test (2 passed).
- `npm run build` — Vite produced `public/build` assets; many Sass deprecation warnings were emitted but build completed successfully.

Environment vars added to `.env` (placeholders — fill after running `passport:install`)
- `PASSPORT_PERSONAL_ACCESS_CLIENT_ID`
- `PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET`
- `PASSPORT_PASSWORD_CLIENT_ID`
- `PASSPORT_PASSWORD_CLIENT_SECRET`
- `DB_QUEUE_CONNECTION` (defaults to `mysql` in `.env`)
- `DB_QUEUE_TABLE` (defaults to `jobs`)
- `DB_QUEUE` (defaults to `default`)
- `DB_QUEUE_RETRY_AFTER` (defaults to `90`)
- `TRUSTED_PROXIES` (comma-separated IPs or `*`)
- `TRUSTED_PROXY_HEADERS` (comma-separated names, e.g. `X_FORWARDED_FOR,X_FORWARDED_PROTO`)

Next recommended steps (manual or I can run them)
1. Backup your database (export / snapshot). Important because migrations can modify schema.
2. Run migrations to apply remaining pending migrations and ensure no duplicates remain:
   - `composer dump-autoload`
   - `php artisan migrate --force`
3. Install Passport and capture client credentials:
   - `php artisan passport:install --force`
   - Copy the printed client IDs/secrets into the `.env` keys listed above.
4. Re-run tests: `php artisan test` and spot-check the app in browser.
5. (Optional) Replace deprecated Sass `@import` rules (convert to `@use` / `@forward`) to silence deprecation warnings.

Notes & caveats
- I replaced `Auth::routes()` with explicit auth route declarations because `laravel/ui` is incompatible with this Laravel 11 setup. If you later adopt Breeze/Fortify/Jetstream, you can remove those explicit routes.
- Several OAuth migration files were made idempotent earlier (added `Schema::hasTable` guards). Ensure database state is correct before running migrations.
- I did not commit any secrets; `PASSPORT_*` values must be added by you after running `passport:install`.

If you want I can:
- Run `php artisan migrate --force` and `php artisan passport:install --force` now and capture the client credentials to provide guidance on where to add them (I will not commit secrets).
- Open a PR that documents these changes and includes `UPGRADE_NOTES.md` as part of the branch.

If you'd like me to proceed with migrations & Passport installation now, confirm and I'll run them (I'll backup nothing on my own; please ensure DB snapshot exists).
