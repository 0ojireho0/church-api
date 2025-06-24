<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ✅ Use 'web' middleware to enable session support
        Route::middleware('web')
            ->prefix('admin') // optional: change to 'api/admin' if needed
            ->group(base_path('routes/authadmin.php'));
    }
}
