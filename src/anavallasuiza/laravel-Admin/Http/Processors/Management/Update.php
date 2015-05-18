<?php
namespace Admin\Http\Processors\Management;

use Admin\Http\Processors\Processor;
use Admin\Library;
use Session;

class Update extends Processor
{
    private function checkBin($bin)
    {
        set_time_limit(0);

        if ((new Library\Shell())->exists($bin)) {
            return true;
        }

        Session::flash('flash-message', [
            'message' => __('%s command not exists', $bin),
            'status' => 'danger',
        ]);

        return false;
    }

    private function exec($bin, $cmd)
    {
        if (!$this->check($bin) || !$this->checkBin($bin)) {
            return false;
        }

        $base = 'cd "'.base_path().'"; ';
        $base .= 'export HOME="'.base_path().'"; ';
        $base .= 'export LC_ALL=en_US.UTF-8; ';

        $log = end((new Library\Shell())->exec($base.$cmd)->getLog());

        if ($log['success']) {
            Session::flash('flash-message', [
                'status' => 'success',
                'message' => __('Environment updated successfully'),
            ]);
        } else {
            Session::flash('flash-message', [
                'status' => 'danger',
                'message' => __('Error updating environment'),
            ]);
        }

        return $log['success'] ? $log['response'] : $log['error'];
    }

    public function git()
    {
        return $this->exec(__FUNCTION__, 'git pull -u origin '.env('GIT_BRANCH'));
    }

    public function composer()
    {
        return $this->exec(__FUNCTION__, 'export COMPOSER_HOME="'.base_path().'"; composer install');
    }

    public function npm()
    {
        return $this->exec(__FUNCTION__, 'npm install');
    }

    public function bower()
    {
        return $this->exec(__FUNCTION__, 'bower update');
    }

    public function grunt()
    {
        return $this->exec(__FUNCTION__, 'grunt');
    }

    public function gulp()
    {
        return $this->exec(__FUNCTION__, 'gulp');
    }
}
