<?php

Route::any('/admin/gettext.js', [
    'as' => 'admin.gettext.js',
    'uses' => 'Admin\Http\Controllers\Admin@gettextJs'
]);

Route::any('/admin/login', [
    'as' => 'admin.login',
    'uses' => 'Admin\Http\Controllers\Admin@login'
]);

Route::group(['prefix' => 'admin', 'before' => 'auth.admin'], function()
{
    Route::get('/', [
        'as' => 'admin',
        'uses' => 'Admin\Http\Controllers\Admin@index'
    ]);

    Route::get('/management/users', [
        'as' => 'admin.management.users.index',
        'uses' => 'Admin\Http\Controllers\Users@index'
    ]);

    Route::any('/management/users/edit/{id?}', [
        'as' => 'admin.management.users.edit',
        'uses' => 'Admin\Http\Controllers\Users@edit'
    ]);

    Route::any('/management/gettext/{locale}', [
        'as' => 'admin.management.gettext',
        'uses' => 'Admin\Http\Controllers\Gettext@index'
    ]);

    Route::any('/management/uploads', [
        'as' => 'admin.management.uploads',
        'uses' => 'Admin\Http\Controllers\Uploads@index'
    ]);

    Route::any('/management/update', [
        'as' => 'admin.management.update',
        'uses' => 'Admin\Http\Controllers\Update@index'
    ]);

    Route::any('/management/logs', [
        'as' => 'admin.management.logs',
        'uses' => 'Admin\Http\Controllers\Logs@index'
    ]);

    Route::any('/management/cache/views', [
        'as' => 'admin.management.cache.views',
        'uses' => 'Admin\Http\Controllers\Cache@index'
    ]);

    Route::any('/management/cache/apc', [
        'as' => 'admin.management.cache.apc',
        'uses' => 'Admin\Http\Controllers\Cache@apc'
    ]);

    Route::any('/management/cache/memcache', [
        'as' => 'admin.management.cache.memcache',
        'uses' => 'Admin\Http\Controllers\Cache@memcache'
    ]);

    Route::any('/management/cache/memcached', [
        'as' => 'admin.management.cache.memcached',
        'uses' => 'Admin\Http\Controllers\Cache@memcached'
    ]);

    Route::any('/management/cache/files', [
        'as' => 'admin.management.cache.files',
        'uses' => 'Admin\Http\Controllers\Cache@files'
    ]);

    Route::get('/admin/logout', [
        'as' => 'admin.logout',
        'uses' => 'Admin\Http\Controllers\Admin@logout'
    ]);
});

require __DIR__.'/filters.php';
