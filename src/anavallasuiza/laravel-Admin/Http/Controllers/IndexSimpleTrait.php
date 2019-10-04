<?php
namespace Admin\Http\Controllers;

trait IndexSimpleTrait
{
    protected function indexView($params)
    {
        $filters = self::initFilters($params['model'], $params['fields']);
        $list = $params['model']->filter($filters);

        if (is_object($processor = $this->processor('exportCsv', null, $list))) {
            return $processor;
        }

        $mode = ($filters['sort'][1] === 'DESC') ? 'ASC' : 'DESC';
        $paginate = self::paginate($filters['paginate'], [20, 50, 100, 200, -1]);

        return view($params['template'], array_merge([
            'list' => ($paginate ? $list->paginate($paginate) : $list->get()),
            'paginate' => $paginate,
            'filter' => $filters,
            'mode' => $mode
        ], array_key_exists('share', $params) ? $params['share'] : []));
    }

    private static function getFields($model, $fields)
    {
        $table = $model->getModel()->getTable();

        $related = array_filter($fields, function ($value) {
            return strpos($value, '.');
        });

        if (empty($related)) {
            return $fields;
        }

        return array_map(function ($value) use ($table) {
            if (strpos($value, '.')) {
                return $value;
            }

            return $table.'.'.$value;
        }, $fields);
    }

    private static function initFilters($model, $fields)
    {
        $all = request()->input();
        $fields = static::getFields($model, $fields);

        foreach (['f-search-c', 'f-search-q', 'f-sort', 'f-rows'] as $field) {
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
            $f['sort'] = [$fields[0], 'DESC'];
        } else {
            list($field, $mode) = explode(' ', $all['f-sort']);

            if (in_array($field, $fields, true)) {
                $f['sort'] = [$field, (($mode === 'DESC') ? 'DESC' : 'ASC')];
            } else {
                $f['sort'] = [$fields[0], 'DESC'];
            }
        }

        $f['paginate'] = (int)$all['f-rows'];

        return $f;
    }

    private static function paginate($value, array $valid)
    {
        if (empty($value) || !in_array($value, $valid, true)) {
            return $valid[0];
        } elseif ($value === -1) {
            return;
        } else {
            return $value;
        }
    }
}
