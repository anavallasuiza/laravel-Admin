<?php namespace Admin\Http\Processors\Management;

use Exception, ZipArchive;
use Admin\Http\Processors\Processor;
use Admin\Library;
use Input, Session, Redirect, Response;

class Gettext extends Processor {
    public function save()
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        Library\Gettext::set(Input::get('locale'), $data['translations']);

        Session::flash('flash-message', [
            'message' => __('Gettext was saved successfully'),
            'status' => 'success'
        ]);

        return Redirect::back();
    }


    public function download()
    {
        if (!class_exists('ZipArchive')) {
            throw new Exception(__('Sorry but you haven\'t enabled ZipArchive (zlib) in your system'));
        }

        $locale = Input::get('locale');
        $locales = array_keys(config('app.locales'));

        if (!in_array($locale, $locales, true)) {
            return Redirect::route('admin.management.gettext.index', $locales[0]);
        }

        $file = tempnam(sys_get_temp_dir(), $locale.'-zip-');

        $zip = new ZipArchive;
        $zip->open($file, ZipArchive::CREATE);

        $zip->addGlob(storage_path('locale/'.$locale.'/LC_MESSAGES/messages.*'), null, [
            'add_path' => '/',
            'remove_all_path' => true
        ]);

        $zip->close();

        return Response::download($file, 'messages-'.$locale.'.zip');
    }
}
