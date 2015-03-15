<?php namespace Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        include __DIR__.'/Library/helpers.php';
        include __DIR__.'/Http/routes.php';

        $this->loadViewsFrom(__DIR__.'/resources/views', 'admin');

        $this->publishes([
            __DIR__.'/config' => config_path('admin'),
        ]);

        $this->publishes([
            __DIR__.'/database/migrations' => base_path('database/migrations'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
    }
}
