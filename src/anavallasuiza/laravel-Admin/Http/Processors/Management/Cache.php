<?php
namespace Admin\Http\Processors\Management;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Admin\Http\Processors\Processor;

class Cache extends Processor
{
    public function apc()
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        apc_clear_cache('opcode');
        apc_clear_cache('user');

        Session::flash('flash-message', [
            'message' => __('Cache was cleared successfully'),
            'status' => 'success',
        ]);

        return Redirect::back();
    }

    public function memcache($form, $Memcache)
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        $Memcache->flush();

        Session::flash('flash-message', [
            'message' => __('Cache was cleared successfully'),
            'status' => 'success',
        ]);

        return Redirect::back();
    }

    public function memcached($form, $Memcache)
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        $Memcache->flush();

        Session::flash('flash-message', [
            'message' => __('Cache was cleared successfully'),
            'status' => 'success',
        ]);

        return Redirect::back();
    }

    public function files()
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return false;
        }

        $cache = public_path('storage/cache/');

        $folders = array_map(function ($folder) {
            return basename($folder);
        }, glob($cache.'*', GLOB_ONLYDIR));

        foreach ($data['delete'] as $folder) {
            if (in_array($folder, $folders, true)) {
                File::deleteDirectory($cache.$folder);
            }
        }

        Session::flash('flash-message', [
            'message' => __('Cache was cleared successfully'),
            'status' => 'success',
        ]);

        return Redirect::back();
    }
}
