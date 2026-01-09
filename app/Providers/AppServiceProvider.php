<?php



namespace App\Providers;



use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;



class AppServiceProvider extends ServiceProvider

{

    /**

     * Register any application services.

     *

     * @return void

     */

    public function register()

    {

        //

    }



    /**

     * Bootstrap any application services.

     *

     * @return void

     */

    public function boot()

    { 

        \URL::forceScheme('http');

        
        // Load mail configuration from database and override default config
        try {
            $setting = Setting::where('delete_status', '0')->first();
            
            if ($setting && $setting->mail_configurtion && is_array($setting->mail_configurtion)) {
                $mailConfig = $setting->mail_configurtion;
                
                // Override mail default
                if (isset($mailConfig['MAIL_MAILER'])) {
                    Config::set('mail.default', $mailConfig['MAIL_MAILER']);
                }
                
                // Override mail from address and name
                if (isset($mailConfig['MAIL_FROM_ADDRESS'])) {
                    Config::set('mail.from.address', $mailConfig['MAIL_FROM_ADDRESS']);
                }
                if (isset($mailConfig['MAIL_FROM_NAME'])) {
                    Config::set('mail.from.name', $mailConfig['MAIL_FROM_NAME']);
                }
                
                // Override SMTP configuration
                if (isset($mailConfig['MAIL_HOST'])) {
                    Config::set('mail.mailers.smtp.host', $mailConfig['MAIL_HOST']);
                }
                if (isset($mailConfig['MAIL_PORT'])) {
                    Config::set('mail.mailers.smtp.port', $mailConfig['MAIL_PORT']);
                }
                if (isset($mailConfig['MAIL_USERNAME'])) {
                    Config::set('mail.mailers.smtp.username', $mailConfig['MAIL_USERNAME']);
                }
                if (isset($mailConfig['MAIL_PASSWORD'])) {
                    Config::set('mail.mailers.smtp.password', $mailConfig['MAIL_PASSWORD']);
                }
                if (isset($mailConfig['MAIL_ENCRYPTION'])) {
                    Config::set('mail.mailers.smtp.encryption', $mailConfig['MAIL_ENCRYPTION']);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the application if database is not available
            \Log::warning('Failed to load mail configuration from database: ' . $e->getMessage());
        }

    }

}

