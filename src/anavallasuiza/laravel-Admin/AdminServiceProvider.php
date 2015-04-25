<?php namespace Admin;

use Illuminate\Support\ServiceProvider;
use Admin\Console\Commands;
use Admin\Library;

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
        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->app->singleton('command.admin.publish.assets', function($app) {
            return new Commands\PublishAssets();
        });

        $this->app->singleton('command.admin.user.new', function($app) {
            return new Commands\UserNew();
        });

        $this->commands('command.admin.publish.assets');
        $this->commands('command.admin.user.new');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.admin.publish.assets',
            'command.admin.user.new'
        ];
    }
}
