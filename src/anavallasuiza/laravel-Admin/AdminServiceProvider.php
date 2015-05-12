<?php

namespace Admin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Admin\Console\Commands;
use Config;
use Request;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    protected static $admin;

    private function isAdmin()
    {
        if (self::$admin !== null) {
            return self::$admin;
        }

        return self::$admin = (config('admin.admin.prefix') === Request::segment(1));
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if (!self::isAdmin()) {
            return null;
        }

        include __DIR__.'/Http/routes.php';

        $this->loadViewsFrom(__DIR__.'/resources/views', 'admin');
        $this->loadViewsFrom(base_path('admin/resources/views'), 'admin-app');

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
        if (self::isAdmin()) {
            $this->registerConfig();
            $this->registerProviders();
            $this->registerAliases();
        }

        $this->registerCommands();
    }

    protected function registerConfig()
    {
        Config::set('session.cookie', config('session.cookie').'_admin');
        Config::set('gettext.cookie', config('gettext.cookie').'_admin');
        Config::set('auth', config('admin.auth'));
    }

    protected function registerProviders()
    {
        $this->app->register('Laravel\Meta\MetaServiceProvider');
        $this->app->register('Laravel\Packer\PackerServiceProvider');
        $this->app->register('Laravel\Gettext\GettextServiceProvider');
    }

    protected function registerAliases()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('Collection', 'Illuminate\Database\Eloquent\Collection');
        $loader->alias('Meta', 'Laravel\Meta\Facade');
        $loader->alias('Packer', 'Laravel\Packer\Facade');
        $loader->alias('Gettext', 'Laravel\Gettext\Facade');
    }

    protected function registerCommands()
    {
        $this->app->singleton('command.admin.publish.assets', function ($app) {
            return new Commands\PublishAssets();
        });

        $this->app->singleton('command.admin.user.new', function ($app) {
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
            'command.admin.user.new',
        ];
    }
}
