<?php namespace Admin\Http\Controllers\Management;

use Input, Redirect;
use Admin\Http\Controllers\Controller;
use Admin\Library, Meta;

class Logs extends Controller
{
    public function index()
    {
        Meta::meta('title', __('System Logs'));

        $Logs = new Library\Logs($data = Input::all());

        return self::view('management.logs.index', [
            'files' => $Logs->getFilesNames(),
            'contents' => $Logs->getContents(),
            'log' => (isset($data['log']) ? $data['log'] : ''),
            'raw' => (isset($data['raw']) ? $data['raw'] : ''),
            'date' => (isset($data['date']) ? $data['date'] : 'day')
        ]);
    }
}
