<?php namespace Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Exception;
use App;
use Auth;
use Config;
use Input;
use Request;
use Response;
use Session;
use View;
use Admin\Library;
use Meta;

abstract class Controller extends BaseController
{
    protected $user;
    protected $locale;

    public function __construct()
    {
        Library\Gettext::load();

        Meta::title(__('Admin Area'));

        Config::set('auth', config('admin.auth'));

        View::share([
            'MODEL' => Request::segment(2),
            'ROUTE' => (Request::segment(3) ?: 'index'),
            'LOCALES' => array_keys(config('app.locales')),
            'LOCALE' => ($this->locale = Session::get('locale')),
            'I' => ($this->user = Auth::user()),
        ]);
    }

    protected static function view($template, $params = [])
    {
        return view('admin::pages.'.$template, $params);
    }

    protected function indexView($model, $fields, $template)
    {
        $filter = self::filter($fields);
        $list = $model->filter($filter);

        if (is_object($action = $this->action('downloadCSV', null, $list))) {
            return $action;
        }

        $mode = ((explode(' ', $filter['sort'])[1] === 'DESC') ? 'ASC' : 'DESC');
        $paginate = self::paginate('rows', [20, 50, 100, 200, -1]);

        if (strstr($template, '\\')) {
            $template = strtolower(str_replace([__NAMESPACE__.'\\', '\\'], ['', '.'], $template));
        }

        return self::view($template.'.index', [
            'list' => ($paginate ? $list->paginate($paginate) : $list->get()),
            'paginate' => $paginate,
            'filter' => $filter,
            'mode' => $mode,
        ]);
    }

    public static function filter(array $fields)
    {
        $all = Input::all();

        foreach (['f-search-c', 'f-search-q', 'f-sort'] as $field) {
            if (!isset($all[$field]) || (strlen($all[$field]) === 0)) {
                $all[$field] = '';
            }
        }

        $f = [];

        if ((strlen($all['f-search-c']) === 0) || !in_array($all['f-search-c'], $fields, true)) {
            $f['search-c'] = '';
        } else {
            $f['search-c'] = $all['f-search-c'];
        }

        if (strlen($all['f-search-q']) === 0) {
            $f['search-q'] = '';
        } else {
            $f['search-q'] = $all['f-search-q'];
        }

        if (empty($all['f-sort'])) {
            $f['sort'] = $fields[0].' DESC';
        } else {
            list($field, $mode) = explode(' ', $all['f-sort']);

            if (in_array($field, $fields, true)) {
                $f['sort'] = $field.' '.(($mode === 'DESC') ? 'DESC' : 'ASC');
            } else {
                $f['sort'] = $fields[0].' DESC';
            }
        }

        return $f;
    }

    public static function paginate($name, array $valid = [], $default = 20)
    {
        $value = (int) Input::get($name);

        if (empty($value) || !in_array($value, $valid, true)) {
            return $default;
        } elseif ($value === -1) {
            return;
        } else {
            return $value;
        }
    }

    protected function getActionClass()
    {
        return str_replace('\\Controllers', '\\Processors', get_class($this));
    }

    protected function action($action, $form = null, $params = null)
    {
        if (!($action = $this->checkAction($action))) {
            return;
        }

        return $this->makeAction($action, $form, $params);
    }

    protected function checkAction($action)
    {
        if (!Request::isMethod('post') || empty($_action = Input::get('_action'))) {
            return;
        }

        if (is_array($action)) {
            return in_array($_action, $action, true) ? $_action : null;
        }

        return (($action === 'AUTO') || ($action === $_action)) ? $_action : null;
    }

    protected function makeAction($action, $form = null, $params = null)
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
            'status' => 'danger',
        ]);

        return false;
    }
}
