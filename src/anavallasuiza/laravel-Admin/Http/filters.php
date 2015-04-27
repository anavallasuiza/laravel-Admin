<?php

Route::filter('admin.logged', function () {
    Config::set('session.cookie', config('session.cookie').'_admin');
    Config::set('auth', config('admin.auth'));

    if (!Auth::guest()) {
        return;
    }

    if (Request::ajax()) {
        return Response::make('Unauthorized', 401);
    } else {
        return Redirect::route('admin.login');
    }
});

Route::filter('admin.admin', function () {
    if (Auth::user()->admin) {
        return;
    }

    throw new Exception(__('Not allowed'), 403);
});
