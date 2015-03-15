<?php namespace Admin\Http\Controllers\Management;

use Input;
use Admin\Http\Controllers\Controller;
use Meta;

class Update extends Controller
{
    public function index()
    {
        if (is_object($action = $this->action('AUTO'))) {
            return $action;
        }

        Meta::meta('title', __('Update environment'));

        return self::view('management.update.index', [
            'action' => Input::get('_action'),
            'response' => $action,
        ]);
    }
}
