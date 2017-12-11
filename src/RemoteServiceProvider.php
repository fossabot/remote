<?php

namespace Jgile\Remote;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class RemoteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Jgile\Remote\Commands\RemoteCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('jgile-remote', function ($app) {
            return new RemoteManager($app);
        });
    }
}
