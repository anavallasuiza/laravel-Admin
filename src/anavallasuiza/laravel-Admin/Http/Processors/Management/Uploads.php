<?php namespace Admin\Http\Processors\Management;

use Auth, File, Input, Session;
use Admin\Http\Processors\Processor;
use Admin\Library, Admin\Models;

class Uploads extends Processor {
    public function fileNew($form, $uploads)
    {
        $files = array_filter(Input::file('files'));

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            $name = strtolower($file->getClientOriginalName());
            $name = preg_replace('/[^\w\.]/', '-', $name);
            $name = preg_replace('/\-+/', '-', $name);

            if ($file->move($uploads, $name)) {
                Models\Log::create([
                    'created_at' => date('Y-m-d H:i:s'),
                    'action' => __FUNCTION__,
                    'description' => (str_replace(public_path(), '', $uploads).$name),
                    'users_id' => Auth::user()->id
                ]);
            }
        }

        Session::flash('flash-message', [
            'message' => __('Files uploaded successfully'),
            'status' => 'success'
        ]);

        return true;
    }

    public function fileDelete($form, $uploads)
    {
        $name = str_replace('..', '', base64_decode(Input::get('name')));

        if (empty($name) || !is_file($uploads.$name)) {
            return false;
        }

        if ($success = File::delete($uploads.$name)) {
            Models\Log::create([
                'created_at' => date('Y-m-d H:i:s'),
                'action' => __FUNCTION__,
                'description' => (str_replace(public_path(), '', $uploads).$name),
                'users_id' => Auth::user()->id
            ]);
        }

        Session::flash('flash-message', [
            'message' => __('File deleted successfully'),
            'status' => 'success'
        ]);

        return $success;
    }

    public function directoryNew($form, $uploads)
    {
        $name = strtolower(str_replace('..', '', Input::get('name')));
        $name = preg_replace('/[^\w\.]/', '-', $name);
        $name = preg_replace('/\-+/', '-', $name);

        if (empty($name) || is_dir($uploads.$name)) {
            return false;
        }

        if ($success = File::makeDirectory($uploads.$name)) {
            Models\Log::create([
                'created_at' => date('Y-m-d H:i:s'),
                'action' => __FUNCTION__,
                'description' => (str_replace(public_path(), '', $uploads).$name),
                'users_id' => Auth::user()->id
            ]);
        }

        Session::flash('flash-message', [
            'message' => __('Directory created successfully'),
            'status' => 'success'
        ]);

        return $success;
    }

    public function directoryDelete($form, $uploads)
    {
        $name = str_replace('..', '', base64_decode(Input::get('name')));

        if (empty($name) || !is_dir($uploads.$name)) {
            return false;
        }

        if ($success = File::deleteDirectory($uploads.$name)) {
            Models\Log::create([
                'created_at' => date('Y-m-d H:i:s'),
                'action' => __FUNCTION__,
                'description' => (str_replace(public_path(), '', $uploads).$name),
                'users_id' => Auth::user()->id
            ]);
        }

        Session::flash('flash-message', [
            'message' => __('Directory deleted successfully'),
            'status' => 'success'
        ]);

        return $success;
    }
}
