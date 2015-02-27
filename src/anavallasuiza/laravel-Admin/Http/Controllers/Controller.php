<?php namespace Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Exception;
use App, Auth, Input, Request, Response, Session, View;
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

    protected static function view($template, $params = [])
    {
        return view('admin::pages.'.$template, $params);
    }

    protected function getActionClass()
    {
        return str_replace('\\Controllers', '\\Processors', get_class($this));
    }

    protected function action($action, $form = null, array $params = [])
    {
        if (!($action = $this->checkAction($action))) {
            return null;
        }

        return $this->makeAction($action, $form, $params);
    }

    protected function checkAction($action)
    {
        if (!Request::isMethod('post') || empty($_action = Input::get('_action'))) {
            return null;
        }

        return (($action === 'AUTO') || ($action === $_action)) ? $_action : null;
    }

    protected function makeAction($action, $form = null, array $params = [])
    {
        try {
            return App::make($this->getActionClass())->$action($form, $params);
        } catch (Exception $e) {
            return $this->setActionMessage($e);
        }
    }

    protected function setActionMessage($e)
    {
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
