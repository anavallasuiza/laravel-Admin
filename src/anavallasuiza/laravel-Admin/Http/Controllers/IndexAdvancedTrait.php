<?php
namespace Admin\Http\Controllers;

trait IndexAdvancedTrait
{
    protected function indexView($params)
    {
        $form = self::loadForm($params['form']);
        $filters = self::initFilters($form);
        $model = self::applyFilters($params['model'], $filters);

        if (is_object($processor = $this->processor('exportCsv', null, $model))) {
            return $processor;
        }

        $mode = ($filters['sort'][1] === 'DESC') ? 'ASC' : 'DESC';
        $paginate = self::paginate($filters['paginate'], [20, 50, 100, 200, -1]);

        return view($params['template'], array_merge([
            'list' => ($paginate ? $model->paginate($paginate) : $model->get()),
            'paginate' => $paginate,
            'filters' => $filters,
            'mode' => $mode,
            'form' => $form
        ], array_key_exists('share', $params) ? $params['share'] : []));
    }

    private static function loadForm($form)
    {
        return $form->loadFromGlobals();
    }

    private static function initFilters($form)
    {
        $v = $form->val();
        $f = ['search' => [], 'sort' => [], 'paginate' => ''];

        foreach ($v['filters'] as $index => $filter) {
            if (strlen($filter['f-search-q'])) {
                $f['search'][$filter['f-search-f']][] = $filter['f-search-q'];
            } else {
                unset($form['filters'][$index]);
            }
        }

        if (!array_key_exists(0, $form['filters'])) {
            $form['filters']->pushVal();
        }

        if ($v['f-sort']) {
            $f['sort'] = explode(' ', $v['f-sort']);
        } else {
            $f['sort'] = ['id', 'DESC'];
        }

        $f['paginate'] = (int)$form['f-rows']->val();

        return $f;
    }

    private static function applyFilters($model, $filters)
    {
        $model->orderBy($filters['sort'][0], $filters['sort'][1]);

        foreach ($filters['search'] as $field => $values) {
            $model = self::applyFilterWhere($model, $field, $values);
        }

        return $model;
    }

    private static function applyFilterWhere($model, $field, $values)
    {
        if (strstr($field, '|')) {
            return self::applyFilterWhereRelated($model, $field, $values);
        }

        return $model->where(function($q) use ($field, $values) {
            foreach ($values as $value) {
                $q->orWhere($field, 'LIKE', '%'.$value.'%');
            }
        });
    }

    private static function applyFilterWhereRelated($model, $field, $values)
    {
        list($table, $field) = explode('|', $field);

        return $model->whereHas($table, function($q) use ($field, $values) {
            foreach ($values as $value) {
                $q->where($field, 'LIKE', '%'.$value.'%');
            }
        });
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
