<?php


namespace App\Providers;




use App\Services\Admin\IpService;
use App\Services\Admin\PermissionService;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\Admin\PermissionService', function () {
            return new PermissionService();
        });
        $this->app->singleton('App\Services\Admin\IpService', function () {
            return new IpService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
