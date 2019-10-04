<?php
namespace Admin\Http\Controllers\Management;

use Admin\Http\Controllers\Controller;
use Meta;

class Update extends Controller
{
    private static $processors = ['git', 'composer', 'npm', 'bower', 'grunt', 'gulp'];

    public function index()
    {
        if (is_object($processor = $this->processor(self::$processors))) {
            return $processor;
        }

        Meta::set('title', __('Update environment'));

        return self::view('management.update.index', [
            'processor' => request()->input('_processor'),
            'response' => $processor,
        ]);
    }
}
