<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
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
        $appUrl = config('app.url');

        if (!$this->app->runningInConsole()) {
            $hostHeader = request()->header('host');
            $scheme = (
                request()->header('x-forwarded-proto') === 'https' ||
                request()->isSecure() ||
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                str_starts_with((string) $appUrl, 'https://')
            ) ? 'https' : 'http';

            // Jika request membawa port di header Host (contoh: localhost:8082 atau 192.168.1.5:8082)
            if ($hostHeader && str_contains($hostHeader, ':')) {
                URL::forceRootUrl($scheme . '://' . $hostHeader);
            } elseif (!empty($appUrl)) {
                // Jika port hilang karena reverse proxy / internal docker port 80, gunakan APP_URL dari .env
                URL::forceRootUrl($appUrl);
            }

            if ($scheme === 'https') {
                URL::forceScheme('https');
            }
        } elseif (!empty($appUrl)) {
            URL::forceRootUrl($appUrl);
            if (str_starts_with((string) $appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }
    }
}
