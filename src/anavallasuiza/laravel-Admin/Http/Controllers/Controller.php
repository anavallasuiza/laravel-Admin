<?php
namespace Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Eusonlito\LaravelProcessor\Controllers\ProcessorTrait;
use Admin\Library;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Route;
use Illuminate\Support\Facades\View;
use Meta;

abstract class Controller extends BaseController
{
    use ProcessorTrait;

    protected $user;
    protected $locale;

    public function __construct()
    {
        Meta::title(__('Admin Area'));

        $route = Route::currentRouteName();
        $database = ($route === 'admin.database');

        View::share([
            'ROUTE' => $route,
            'TABLE' => ($database ? Request::segment(3) : null),
            'SECTION' => ($database ? Request::segment(4) : null),
            'LOCALES' => config('gettext.locales'),
            'LOCALE' => ($this->locale = Library\Helpers::locale()),
            'I' => ($this->user = Auth::user()),
        ]);
    }

    protected static function view($template, $params = [])
    {
        return view('admin::pages.'.$template, $params);
    }
}
