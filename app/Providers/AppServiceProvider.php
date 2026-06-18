<?php
namespace App\Providers;

use App\Services\SkorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SkorService::class);
    }

    public function boot(): void
    {
        //
    }
}