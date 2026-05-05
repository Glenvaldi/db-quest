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
        // Taktik Anti-Gagal: Memaksa HTTPS aktif saat berada di server production
        // Menggunakan $this->app->environment agar aman meskipun konfigurasi di-cache oleh server
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
