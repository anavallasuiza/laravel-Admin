<?php
namespace Admin\Http\Controllers\Management;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Input;
use View;
use Admin\Http\Controllers\Controller;
use Admin\Library;
use Meta;

class Cache extends Controller
{
    public function views()
    {
        $storage = storage_path('framework/views/');

        $files = array_map(function ($file) {
            return basename($file);
        }, glob($storage.'*'));

        if (($view = Input::get('view')) && in_array($view, $files, true)) {
            $contents = file_get_contents($storage.$view);
        } else {
            $contents = '';
        }

        Meta::set('title', __('Cache: Views'));

        return self::view('management.cache.views', [
            'files' => $files,
            'view' => $view,
            'contents' => $contents,
        ]);
    }

    public function apc()
    {
        Meta::set('title', __('Cache: APC'));

        if (!function_exists('apc_cache_info')) {
            return View::make('admin::molecules.alert-extends', [
                'extends' => 'management.cache.layout',
                'section' => 'subcontent',
                'status' => 'danger',
                'message' => __('You have not enabled APC in your system'),
            ]);
        }

        if (is_object($processor = $this->processor(__FUNCTION__))) {
            return $processor;
        }

        $stats = apc_sma_info();

        $total = $stats['num_seg'] * $stats['seg_size'];
        $used = $total - $stats['avail_mem'];

        return self::view('management.cache.apc', [
            'total' => round($total / 1024 / 1024),
            'used' => round($used / 1024 / 1024),
            'percent' => round(($used * 100) / $total),
        ]);
    }

    private function loadCacheMemcache($class)
    {
        if (!class_exists($class)) {
            return View::make('admin::molecules.alert-extends', [
                'extends' => 'management.cache.layout',
                'section' => 'subcontent',
                'status' => 'danger',
                'message' => __('You have not enabled %s in your system', $class),
            ]);
        }

        try {
            $Memcache = new Library\Memcache(new $class());
        } catch (Exception $e) {
            return View::make('admin::molecules.alert-extends', [
                'extends' => 'management.cache.layout',
                'section' => 'subcontent',
                'status' => 'danger',
                'message' => $e->getMessage(),
            ]);
        }

        return $Memcache;
    }

    public function memcache()
    {
        Meta::set('title', __('Cache: Memcache'));

        $Memcache = $this->loadCacheMemcache('Memcache');

        if (!($Memcache instanceof Library\Memcache)) {
            return $Memcache;
        }

        if (is_object($processor = $this->processor(__FUNCTION__, null, $Memcache))) {
            return $processor;
        }

        $stats = $Memcache->getStats();

        $total = $stats['limit_maxbytes'];
        $used = $stats['bytes'];

        return self::view('management.cache.memcache', [
            'total' => round($total / 1024 / 1024),
            'used' => round($used / 1024 / 1024),
            'percent' => round(($used * 100) / $total),
        ]);
    }

    public function memcached()
    {
        Meta::set('title', __('Cache: Memcached'));

        $Memcache = $this->loadCacheMemcache('Memcached');

        if (!($Memcache instanceof Library\Memcache)) {
            return $Memcache;
        }

        if (is_object($processor = $this->processor(__FUNCTION__, null, $Memcache))) {
            return $processor;
        }

        $stats = array_values($Memcache->getStats())[0];

        $total = $stats['limit_maxbytes'];
        $used = $stats['bytes'];

        return self::view('management.cache.memcached', [
            'total' => round($total / 1024 / 1024),
            'used' => round($used / 1024 / 1024),
            'percent' => round(($used * 100) / $total),
        ]);
    }

    public function files()
    {
        if (is_object($processor = $this->processor(__FUNCTION__))) {
            return $processor;
        }

        $total = 0;
        $folders = [];

        foreach (glob(public_path('storage/cache/').'*', GLOB_ONLYDIR) as $folder) {
            $total = $files = $size = 0;

            $iterator = new RecursiveDirectoryIterator($folder);

            foreach (new RecursiveIteratorIterator($iterator) as $file) {
                $size += $file->getSize();
                ++$files;
            }

            $total += $size;

            $folders[] = [
                'name' => basename($folder),
                'files' => $files,
                'size' => $size,
            ];
        }

        Meta::set('title', __('Cache: Files'));

        if (empty($folders)) {
            return View::make('admin::molecules.alert-extends', [
                'extends' => 'management.cache.layout',
                'section' => 'subcontent',
                'status' => 'success',
                'message' => __('Cache folder is empty'),
            ]);
        }

        array_walk($folders, function (&$folder) use ($total) {
            $folder['percent'] = round(($folder['size'] * 100) / $total);
            $folder['size'] = round($folder['size'] / 1024 / 1024);
        });

        return self::view('management.cache.files', [
            'folders' => $folders,
            'total' => round($total / 1024 / 1024),
        ]);
    }
}
