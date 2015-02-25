<?php

Route::filter('auth.admin', function()
{
    if (!Auth::guest()) {
        return;
    }

    if (Request::ajax()) {
        return Response::make('Unauthorized', 401);
    } else {
        return Redirect::route('admin.login');
    }
});