<?php
require __DIR__.'/filters.php';

$prefix = config('admin.admin.prefix');

Route::get('/'.$prefix.'/gettext.js', [
    'as' => 'admin.gettext.js',
    'uses' => 'Admin\Http\Controllers\Admin@gettextJs'
]);

Route::any('/'.$prefix.'/login', [
    'as' => 'admin.login',
    'uses' => 'Admin\Http\Controllers\Admin@login'
]);

Route::group(['prefix' => $prefix, 'before' => 'admin.logged'], function()
{
    Route::get('/', [
        'as' => 'admin.index',
        'uses' => 'Admin\Http\Controllers\Admin@index'
    ]);

    Route::get('/logout', [
        'as' => 'admin.logout',
        'uses' => 'Admin\Http\Controllers\Admin@logout'
    ]);

    Route::group(['before' => 'admin.admin'], function ()
    {
        Route::get('/management/users', [
            'as' => 'admin.management.users.index',
            'uses' => 'Admin\Http\Controllers\Management\Users@index'
        ]);

        Route::any('/management/users/edit/{id?}', [
            'as' => 'admin.management.users.edit',
            'uses' => 'Admin\Http\Controllers\Management\Users@edit'
        ]);

        Route::any('/management/gettext/{locale}', [
            'as' => 'admin.management.gettext.index',
            'uses' => 'Admin\Http\Controllers\Management\Gettext@index'
        ]);

        Route::any('/management/uploads', [
            'as' => 'admin.management.uploads.index',
            'uses' => 'Admin\Http\Controllers\Management\Uploads@index'
        ]);

        Route::any('/management/update', [
            'as' => 'admin.management.update.index',
            'uses' => 'Admin\Http\Controllers\Management\Update@index'
        ]);

        Route::any('/management/logs', [
            'as' => 'admin.management.logs.index',
            'uses' => 'Admin\Http\Controllers\Management\Logs@index'
        ]);

        Route::any('/management/cache/views', [
            'as' => 'admin.management.cache.views',
            'uses' => 'Admin\Http\Controllers\Management\Cache@index'
        ]);

        Route::any('/management/cache/apc', [
            'as' => 'admin.management.cache.apc',
            'uses' => 'Admin\Http\Controllers\Management\Cache@apc'
        ]);

        Route::any('/management/cache/memcache', [
            'as' => 'admin.management.cache.memcache',
            'uses' => 'Admin\Http\Controllers\Management\Cache@memcache'
        ]);

        Route::any('/management/cache/memcached', [
            'as' => 'admin.management.cache.memcached',
            'uses' => 'Admin\Http\Controllers\Management\Cache@memcached'
        ]);

        Route::any('/management/cache/files', [
            'as' => 'admin.management.cache.files',
            'uses' => 'Admin\Http\Controllers\Management\Cache@files'
        ]);
    });
});
