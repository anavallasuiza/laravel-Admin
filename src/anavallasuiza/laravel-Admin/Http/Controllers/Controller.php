<?php namespace Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Exception;
use App, Auth, Config, Request, Session, View;
use Gettext;

abstract class Controller extends BaseController
{
    use ControllerTrait;

    protected $user;
    protected $locale;

    public function __construct()
    {
        Gettext::load();

        View::share([
            'MODEL' => Request::segment(2),
            'ROUTE' => (Request::segment(3) ?: 'index'),
            'LOCALES' => array_keys(config('app.locales')),
            'LOCALE' => ($this->locale = Session::get('locale')),
            'I' => ($this->user = Auth::user())
        ]);
    }

    public static function view($template, $params = [])
    {
        return view('admin::pages.'.$template, $params);
    }

    public function action($action, \FormManager\Form $form = null, $params = [])
    {
        if (($action === 'AUTO') && empty($_action = Input::get('_action'))) {
            return null;
        }

        if (($form !== null) && !Request::isMethod('post')) {
            return null;
        }

        $action = ($action === 'AUTO') ? $_action : $action;

        try {
            $class = str_replace('\\Controllers', '\\Processors', get_class($this));
            return App::make($class)->$action($form, $params);
        } catch (Exception $e) {
            $message = $e->getMessage();

            if (config('debug')) {
                $message = '['.$e->getFile().' - '.$e->getLine().'] '.$message;
            }

            if (Request::ajax()) {
                return Response::make($message, (($e->getCode() === 404) ? 404 : 500));
            }

            Session::flash('flash-message', [
                'message' => $message,
                'status' => 'danger'
            ]);

            return false;
        }
    }
}
