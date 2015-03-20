<?php namespace Admin\Http\Controllers\Management;

use App\Schemas;
use Admin\Http\Controllers\Controller;

class Database extends Controller
{
    public function index()
    {
        $tables = (new Schemas\App())->load();

        return self::view('management.database.index', [
            'sql' => $tables->toSql()
        ]);
    }
}