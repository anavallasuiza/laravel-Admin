composer.json = "eusonlito/laravel-admin": "master-dev"

config/app.php

'providers' => 
        'App\Providers\GettextServiceProvider',
        'Laravel\Meta\MetaServiceProvider',
        'Laravel\Packer\PackerServiceProvider'
        'Admin\AdminServiceProvider',

'aliases' => 
        'Gettext'   => 'App\Facades\Gettext',
        'Meta'     => 'Laravel\Meta\Facade',
        'Packer'   => 'Laravel\Packer\Facade'

php artisan vendor:publish

Add to app/Console/Kernel.php

        'Admin\Console\Commands\PublishAssets',
        'Admin\Console\Commands\UserNew'

cd vendor/eusonlito/laravel-admin

gulp build

cd ../../..

php artisan admin:publish:assets