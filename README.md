Laravel Admin (On Develop)
=====

Starting a base to base admin managemet based in database tables.

Thanks to https://github.com/almasaeed2010/AdminLTE to this great Admin CSS/Javascript/HTML Theme.

# Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "anavallasuiza/laravel-admin": "master-dev"
    }
}
```

Configure Laravel Service Providers and Aliases in `config/app.php`:

```php
'providers' => [
    ...

    'App\Providers\GettextServiceProvider',
    'Laravel\Meta\MetaServiceProvider',
    'Laravel\Packer\PackerServiceProvider'
    'Admin\AdminServiceProvider',

    ...
]

'aliases' => [
    ...

    'Gettext'   => 'App\Facades\Gettext',
    'Meta'     => 'Laravel\Meta\Facade',
    'Packer'   => 'Laravel\Packer\Facade',

    ...
]
```

Publish the base admin configuration:

```bash
php artisan vendor:publish
```

Update `app/Console/Kernel.php` with this new commands:

```php
protected $commands = [
    ...

    'Admin\Console\Commands\PublishAssets',
    'Admin\Console\Commands\UserNew',

    ...
];
```

Publish admin assets with:

```bash
php artisan admin:publish:assets
```

And finally, launch new migration and create your first admin user:

```bash
php artisan migrate

# php artisan admin:user:new Name user password admin
php artisan admin:user:new Admin admin admin true
```

Check now to login into http://mydomain.com/admin
