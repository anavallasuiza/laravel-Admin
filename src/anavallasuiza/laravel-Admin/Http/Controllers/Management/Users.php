<?php

namespace Admin\Http\Controllers\Management;

use Admin\Http\Controllers\Controller;
use Admin\Http\Controllers\ControllerIndexSimpleTrait;
use Admin\Models;
use Meta;
use View;
use App;

class Users extends Controller
{
    use ControllerIndexSimpleTrait;

    public function index()
    {
        Meta::meta('title', __('List Admin Users'));

        return $this->indexView([
            'model' => App::make('Admin\\Models\\User'),
            'template' => 'admin::pages.management.users.index',
            'fields' => ['user', 'name', 'admin', 'enabled']
        ]);
    }

    private function getRow($id)
    {
        if (empty($id)) {
            return new Models\User();
        }

        return Models\User::where('id', $id)->firstOrFail();
    }

    public function edit($id = '')
    {
        $row = $this->getRow($id);

        if ($id && is_object($processor = $this->processor('delete', null, $row))) {
            return $processor;
        }

        $form = new Forms\Users\Edit();

        if (is_object($processor = $this->processor(__FUNCTION__, $form, $row))) {
            return $processor;
        }

        if (empty($row->id)) {
            Meta::meta('title', __('New'));
        } else {
            Meta::meta('title', __('Edit "%s"', $row->name));

            if ($processor === null) {
                $form->preload($row);
            }

            self::ViewComposerLogs($row);
            self::ViewComposerSessions($row);
        }

        return self::view('management.users.edit', [
            'form' => $form,
            'row' => $row,
        ]);
    }

    private static function ViewComposerLogs($row)
    {
        View::composer('admin::pages.management.users.logs', function ($view) use ($row) {
            $rows = Models\Log::where('admin_users_id', $row->id)
                ->orderBy('id', 'DESC')
                ->get()->each(function ($row) {
                    $row->related_table = str_replace('_', '-', $row->related_table);
                });

            $view->with('rows', $rows);
        });
    }

    private static function ViewComposerSessions($row)
    {
        View::composer('admin::pages.management.users.sessions', function ($view) use ($row) {
            $view->with('rows', Models\Session::where('admin_users_id', $row->id)
                ->orderBy('id', 'DESC')
                ->get());
        });
    }
}
