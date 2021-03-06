<?php
namespace Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Meta;

class Admin extends Controller
{
    public function error($exception, $code)
    {
        Meta::set('title', __('Error %s', $code));

        $page = ($code === 500) ? 500 : 404;

        return Response::view('admin.pages.'.$page, [
            'code' => $code,
            'message' => $exception->getMessage(),
        ], $code);
    }

    public function login()
    {
        if ($this->user) {
            return Redirect::route('admin.index');
        }

        $form = new Forms\Users\Login();

        if (is_object($processor = $this->processor(__FUNCTION__, $form))) {
            return $processor;
        }

        Meta::set('title', __('Login into admin area'));

        return self::view('login', [
            'form' => $form,
        ]);
    }

    public function logout()
    {
        return $this->makeProcessor(__FUNCTION__);
    }

    public function index()
    {
        Meta::set('title', __('Index'));

        return self::view('index');
    }

    public function gettextJs()
    {
        return Response::make(self::view('gettext-js'))->header('Content-Type', 'application/javascript');
    }
}
