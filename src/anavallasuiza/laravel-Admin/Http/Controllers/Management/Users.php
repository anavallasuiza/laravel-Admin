<?php namespace Admin\Http\Controllers\Management;

use View;
use Admin\Http\Controllers\Controller;
use Admin\Models;
use Meta;

class Users extends Controller
{
    public function index()
    {
        Meta::meta('title', __('List Admin Users'));

        $fields = ['user', 'name', 'admin', 'enabled'];

        return $this->indexView(Models\User::orderBy('id', 'DESC'), $fields, __CLASS__);
    }

    public function edit($id = '')
    {
        $form = (new Forms\User())->edit();

        if (is_object($action = $this->action(__FUNCTION__, $form))) {
            return $action;
        }

        $row = new \stdClass();
        $row->id = 0;

        if ($id) {
            $row = Models\User::where('id', '=', $id)->firstOrFail();

            Meta::meta('title', __('Edit "%s"', $row->name));

            if ($action === null) {
                $form->load($row);
            }

            View::composer('admin::pages.management.users.logs', function ($view) use ($row) {
                $rows = Models\Log::where('users_id', '=', $row->id)
                    ->orderBy('id', 'DESC')
                    ->get()->each(function ($row) {
                        $row->related_table = str_replace('_', '-', $row->related_table);
                    });

                $view->with('rows', $rows);
            });

            View::composer('admin::pages.management.users.sessions', function ($view) use ($row) {
                $view->with('rows', Models\Session::where('users_id', '=', $row->id)
                    ->orderBy('id', 'DESC')
                    ->get());
            });
        } else {
            Meta::meta('title', __('New'));
        }

        return self::view('management.users.edit', [
            'form' => $form,
            'row' => $row,
        ]);
    }
}
