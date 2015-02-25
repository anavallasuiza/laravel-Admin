<?php namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Forms;
use Meta;

class Admin extends Controller
{
    public function error($exception, $code)
    {
        $page = ($code === 500) ? 500 : 404;

        return Response::view('admin.pages.'.$page, [
            'code' => $code,
            'message' => $exception->getMessage()
        ], $code);
    }

    public function login()
    {
        $form = (new Forms\Admin)->login();

        if (is_object($action = $this->action(__FUNCTION__, $form))) {
            return $action;
        }

        Meta::meta('title', __('Login in your user area'));

        return self::view('login', [
            'form' => $form
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::route('admin.login');
    }

    public function gettextJs()
    {
        return Response::make(self::view('gettext-js'))->header('Content-Type', 'application/javascript');
    }
}
