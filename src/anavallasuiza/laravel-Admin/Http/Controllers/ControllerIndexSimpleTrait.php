<?php
namespace Admin\Http\Controllers;

use Input;

trait ControllerIndexSimpleTrait
{
    protected function indexView($params)
    {
        $filter = $this->filter($params['fields']);
        $list = $params['model']->filter($filter);

        if (is_object($processor = $this->processor('downloadCSV', null, $list))) {
            return $processor;
        }

        $mode = ((explode(' ', $filter['sort'])[1] === 'DESC') ? 'ASC' : 'DESC');
        $paginate = self::paginate('rows', [20, 50, 100, 200, -1]);

        return view($params['template'], [
            'list' => ($paginate ? $list->paginate($paginate) : $list->get()),
            'paginate' => $paginate,
            'filter' => $filter,
            'mode' => $mode,
        ]);
    }

    public function filter($fields)
    {
        $all = Input::all();

        foreach (['f-search-c', 'f-search-q', 'f-sort'] as $field) {
            if (!isset($all[$field]) || (strlen($all[$field]) === 0)) {
                $all[$field] = '';
            }
        }

        $f = [];

        if (strlen($all['f-search-q']) === 0) {
            $f['search-q'] = '';
        } else {
            $f['search-q'] = $all['f-search-q'];
        }

        if ((strlen($all['f-search-c']) === 0) || !in_array($all['f-search-c'], $fields, true)) {
            $f['search-c'] = $f['search-q'] ? $fields : '';
        } else {
            $f['search-c'] = $all['f-search-c'];
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
}
