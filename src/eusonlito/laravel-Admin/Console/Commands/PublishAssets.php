<?php namespace Admin\Console\Commands;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PublishAssets extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:publish:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets in public/assets/admin folder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $build = base_path('vendor/eusonlito/laravel-admin/assets/build');
        $public = public_path('assets/admin/build');

        if (!is_dir($build)) {
            $this->error(__('%s path not exists. You must generate it with `gulp build`.'));
            return false;
        }

        File::deleteDirectory($public);
        File::copyDirectory($build, $public);

        $this->info('Admin assets published successfully');
    }

}
