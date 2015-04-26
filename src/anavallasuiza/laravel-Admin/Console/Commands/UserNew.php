<?php namespace Admin\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Hash;
use Admin\Models;

class UserNew extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:user:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Admin User';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->option();

        $exists = Models\User::where('user', '=', $data['user'])->first();

        if ($exists) {
            $this->error(__('User %s alread exists', $data['user']));

            return false;
        }

        try {
            Models\User::create([
                'name' => $data['name'],
                'user' => $data['user'],
                'password' => Hash::make($data['password']),
                'admin' => ($data['admin'] && ($data['admin'] !== 'false')),
                'enabled' => 1
            ]);
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return false;
        }

        $this->info('User created successfully');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['name', '', InputOption::VALUE_REQUIRED, 'User name.'],
            ['user', '', InputOption::VALUE_REQUIRED, 'Login user.'],
            ['password', '', InputOption::VALUE_REQUIRED, 'Login password.'],
            ['admin', '', InputOption::VALUE_OPTIONAL, 'Set User as Admin.']
        ];
    }
}
