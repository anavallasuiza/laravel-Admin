<?php namespace Admin\Http\Controllers\Management;

use Config;
use App\Schemas;
use Admin\Http\Controllers\Controller;
use Database\Diff\DD as DbDiff;

class Database extends Controller
{
    public function index()
    {
        $tables = new Schemas\App();
        $tables->up();

        $DD = new DbDiff;

        $DD->loadString('mysql', $tables->toSql());
        $DD->loadDatabase(Config::get('database.connections.mysql'));

        dd($DD->diff());

        return self::view('management.database.index', [
            'db1' => trim(str_replace("\n", "\n\n", $db1)),
            'db2' => $db2
        ]);
    }
}