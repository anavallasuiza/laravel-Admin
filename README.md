Laravel Admin (On Develop)
=====

Starting a base to base admin managemet based in database tables.

Thanks to https://github.com/almasaeed2010/AdminLTE to this great Admin CSS/Javascript/HTML Theme.

# Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "anavallasuiza/laravel-admin": "5.1.*-dev"
    }
}
```

Configure Laravel Service Providers/Aliases in `config/app.php`:

```php
'providers' => [
    ...

    Admin\AdminServiceProvider::class,

    ...
]

'aliases' => [
   ...

   'Input' => Illuminate\Support\Facades\Input::class,

   ...
]
```

Configure `app/Http/Kernel.php` with Middlewares:

```php
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    ...

    'admin' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ]
];

protected $routeMiddleware = [
    ...

    'admin.auth' => \Admin\Http\Middleware\Authenticate::class,
    'admin.admin' => \Admin\Http\Middleware\Admin::class,
];
```

Publish the base admin configuration:

```bash
php artisan vendor:publish
```

Publish admin assets with:

```bash
php artisan admin:publish:assets
```

Mirate admin tables

```bash
php artisan migrate
```

And finally, create your first admin user:

```bash
php artisan admin:user:new --name Admin --user admin --password admin --admin true
```

Check now to login into http://mydomain.com/admin
