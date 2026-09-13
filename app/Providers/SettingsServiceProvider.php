<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (!app()->runningInConsole() || Schema::hasTable('settings')) {

            // Fetch configuration maps from Cache (Stored for 24 hours / 86400 seconds)
            $settings = Cache::remember('global_system_settings', 86400, function () {
                return Setting::pluck('value', 'key')->toArray();
            });

            // Dynamically override core framework configuration variables at runtime
            if (!empty($settings)) {
                // Application Branding
                if (isset($settings['app_name'])) {
                    Config::set('app.name', $settings['app_name']);
                    Config::set('adminlte.title', $settings['app_name']);
                }

                // Global SMTP Mail Configurations
                if (isset($settings['mail_driver'])) {
                    Config::set('mail.mailers.smtp.transport', $settings['mail_driver']);
                    Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? '');
                    Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? 587);
                    Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? '');
                    Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? '');
                    Config::set('mail.from.address', $settings['mail_from_address'] ?? 'noreply@system.com');
                    Config::set('mail.from.name', $settings['app_name'] ?? 'System Admin');
                }

                // System Operational Feature Flags
                if (isset($settings['allow_registration'])) {
                    Config::set('settings.registration_enabled', filter_var($settings['allow_registration'], FILTER_VALIDATE_BOOLEAN));
                }
            }
        }
    }
}
