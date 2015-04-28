<?php

Route::filter('admin.logged', function () {
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
