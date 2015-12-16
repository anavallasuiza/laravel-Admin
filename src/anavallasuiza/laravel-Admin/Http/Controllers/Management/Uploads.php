<?php
namespace Admin\Http\Controllers\Management;

use Input;
use Meta;
use Admin\Http\Controllers\Controller;

class Uploads extends Controller
{
    private static $processors = ['fileNew', 'directoryNew', 'fileDelete', 'directoryDelete'];

    public function index()
    {
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

        if (is_object($processor = $this->processor(self::$processors, null, $uploads))) {
            return $processor;
        }

        list($files, $directories) = self::getFilesDirectories($uploads, $dir, $public);

        Meta::meta('title', __('Uploads'));

        return self::view('management.uploads.index', [
            'location' => self::getLocation($dir),
            'directories' => $directories,
            'files' => $files,
        ]);
    }

    private static function getFilesDirectories($uploads, $dir, $public)
    {
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

        return [$files, $directories];
    }

    private static function getLocation($dir)
    {
        $location = [];
        $acum = '';

        foreach (explode('/', $dir) as $path) {
            $location[] = [
                'dir' => base64_encode($acum .= $path.'/'),
                'name' => $path,
            ];
        }

        return $location;
    }
}
