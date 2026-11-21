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
        if (!$this->app->runningInConsole()) {
            if (
                request()->header('x-forwarded-proto') === 'https' ||
                request()->isSecure() ||
                (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                str_starts_with(config('app.url'), 'https://')
            ) {
                URL::forceScheme('https');
            }
        } elseif (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
