<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\NotificationComposer;

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
        // Mendaftarkan NotificationComposer untuk komponen nav
        view()->composer(
            [
                'layouts.navbars.auth.nav',
            ],
            NotificationComposer::class
        );

        // Jika nav.blade.php Anda berada di lokasi lain, sesuaikan pathnya
        // Contoh jika berada di folder layouts:
        // view()->composer('layouts.nav', NotificationComposer::class);
    }
}
