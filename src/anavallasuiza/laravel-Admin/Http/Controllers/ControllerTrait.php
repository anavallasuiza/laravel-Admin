<?php namespace Admin\Http\Controllers;

use Admin\Library\Html;

trait ControllerTrait
{
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

}
