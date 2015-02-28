<?php namespace Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Exception;
use App, Auth, Input, Request, Response, Session, View;
use Gettext, Meta;

abstract class Controller extends BaseController
{
    protected $user;
    protected $locale;

    public function __construct()
    {
        Gettext::load();

        Meta::title(__('Admin Area'));

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

    public function indexBasic($list, $template)
    {
        if ($search = Input::get('search')) {
            $list->search($search);
        }

        if (is_object($action = $this->action('downloadCSV', null, $list))) {
            return $action;
        }

        $paginate = Libs\Utils::paginate('rows', [20, 50, 100, 200, -1]);

        if (strstr($template, '\\')) {
            $template = strtolower(substr(strrchr($template, '\\'), 1));
        }

        return self::view($template.'.index', [
            'list' => ($paginate ? $list->paginate($paginate) : $list->get()),
            'paginate' => $paginate,
            'search' => $search
        ]);
    }

    public function indexFilter($model, $fields, $template)
    {
        $filter = Libs\Utils::filter($fields);
        $model = App::make('\\App\\Models\\'.$model);
        $list = $model::filter($filter);

        if (is_object($action = $this->action('downloadCSV', null, $list))) {
            return $action;
        }

        $mode = ((explode(' ', $filter['sort'])[1] === 'DESC') ? 'ASC' : 'DESC');
        $paginate = Libs\Utils::paginate('rows', [20, 50, 100, 200, -1]);

        if (strstr($template, '\\')) {
            $template = strtolower(substr(strrchr($template, '\\'), 1));
        }

        return self::view($template.'.index', [
            'list' => ($paginate ? $list->paginate($paginate) : $list->get()),
            'paginate' => $paginate,
            'filter' => $filter,
            'mode' => $mode
        ]);
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
