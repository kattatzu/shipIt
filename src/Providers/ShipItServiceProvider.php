<?php namespace Kattatzu\ShipIt\Providers;

use Kattatzu\ShipIt\ShipIt;
use Illuminate\Support\ServiceProvider;

class ShipItServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->publishes([
            __DIR__ . '/../config/config.php' => app()->basePath() . '/config/shipit.php',
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ShipIt::class, function ($app) {
            $email = config('shipit.email', env('SHIPIT_EMAIL'));
            $token = config('shipit.token', env('SHIPIT_TOKEN'));


            return new ShipIt($email, $token);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ShipIt::class];
    }
}
