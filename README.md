Laravel Admin (On Develop)
=====

Starting a base to base admin managemet based in database tables.

Thanks to https://github.com/almasaeed2010/AdminLTE to this great Admin CSS/Javascript/HTML Theme.

# Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "anavallasuiza/laravel-admin": "dev-develop"
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
