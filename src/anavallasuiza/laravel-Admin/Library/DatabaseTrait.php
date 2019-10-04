<?php
namespace Admin\Library;

use App;

trait DatabaseTrait
{
    protected static function getClass()
    {
        return class_basename(__CLASS__);
    }

    protected static function getModel($model = null)
    {
        $model = self::makeModel($model);

        return method_exists($model, 'withTrashed') ? $model->withTrashed() : $model;
    }

    protected static function getForm($function, $form = null)
    {
        if (is_object($form)) {
            return $form;
        }

        $form = $form ?: class_basename(__CLASS__);

        return App::make('Admin\Http\Controllers\Database\Forms\\'.$form)->$function();
    }

    protected static function getRow($row = null)
    {
        if (is_object($row)) {
            return $row;
        }

        $model = self::makeModel();

        if (empty($row)) {
            return $model;
        }

        if (method_exists($model, 'withTrashed')) {
            $model = $model->withTrashed();
        }

        return $model->where('id', (int)$row)->firstOrFail();
    }

    private static function makeModel($model = null)
    {
        return App::make('App\Models\\'.($model ?: class_basename(__CLASS__)));
    }
}
