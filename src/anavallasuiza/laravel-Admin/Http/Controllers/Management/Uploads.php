<?php namespace Admin\Http\Controllers\Management;

use Input;
use Admin\Http\Controllers\Controller;
use Meta;

class Uploads extends Controller
{
    public function index()
    {
        Meta::meta('title', __('Uploads'));

        $public = 'storage/uploads/';
        $base = public_path($public);

        if (!is_dir($base) && !@mkdir($base, 0700, true)) {
            throw new Exception(__('Folder %s not exists and can not be created', $public));
        }

        if ($dir = Input::get('dir')) {
            $public .= $dir = str_replace('..', '', base64_decode($dir));
        }

        $uploads = public_path($public);

        if (!is_dir($uploads)) {
            throw new Exception(__('Folder %s not exists', $public));
        } elseif (!is_writable($uploads)) {
            throw new Exception(__('Folder %s has not write permissions', $public));
        }

        if (is_object($action = $this->action('AUTO', null, $uploads))) {
            return $action;
        }

        $directories = $files = [];

        foreach (glob($uploads.'*', GLOB_MARK) as $each) {
            $each = str_replace($uploads, '', $each);

            if (substr($each, -1) === '/') {
                $directories[] = [
                    'dir' => base64_encode($dir.$each),
                    'slug' => base64_encode($each),
                    'name' => $each,
                ];
            } else {
                $files[] = [
                    'slug' => base64_encode($each),
                    'url' => asset($public.$each),
                    'name' => $each,
                ];
            }
        }

        $location = [];
        $acum = '';

        foreach (explode('/', $dir) as $path) {
            $location[] = [
                'dir' => base64_encode($acum .= $path.'/'),
                'name' => $path,
            ];
        }

        return self::view('management.uploads.index', [
            'location' => $location,
            'directories' => $directories,
            'files' => $files,
        ]);
    }
}
