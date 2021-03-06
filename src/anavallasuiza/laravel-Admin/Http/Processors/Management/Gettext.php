<?php
namespace Admin\Http\Processors\Management;

use Exception;
use ZipArchive;
use Admin\Http\Processors\Processor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class Gettext extends Processor
{
    public function save($form, $config)
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        app('gettext')->setEntries(request()->input('locale'), $data['translations']);

        Session::flash('flash-message', [
            'message' => __('Gettext was saved successfully'),
            'status' => 'success',
        ]);

        return Redirect::back();
    }

    public function download($form, $config)
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        if (!class_exists('ZipArchive')) {
            throw new Exception(__('Sorry but you haven\'t enabled ZipArchive (zlib) in your system'));
        }

        $locale = request()->input('locale');

        $file = tempnam(sys_get_temp_dir(), $locale.'-zip-');

        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::CREATE);

        $storage = $config['storage'];
        $storage = base_path($storage.'/'.$locale.'/LC_MESSAGES');
        $storage .= '/'.$config['domain'];

        $zip->addGlob($storage.'.*', null, [
            'add_path' => '/',
            'remove_all_path' => true,
        ]);

        $zip->close();

        return Response::download($file, $config['domain'].'-'.$locale.'.zip');
    }
}
