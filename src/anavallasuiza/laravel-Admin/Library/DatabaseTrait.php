<?php
namespace Admin\Library;

use App, Auth;

trait DatabaseTrait
{
    protected static function getClass()
    {
        return class_basename(__CLASS__);
    }

    protected static function getModel($model = null)
    {
        $model = $model ?: class_basename(__CLASS__);

        return App::make('App\Models\\'.$model)->withTrashed();
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

        $model = self::getModel();

        return $row ? $model->where('id', (int)$row)->firstOrFail() : $model;
    }
}
