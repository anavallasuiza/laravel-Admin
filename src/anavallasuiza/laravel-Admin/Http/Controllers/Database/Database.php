<?php namespace Admin\Http\Controllers\Database;

use Admin\Http\Controllers\Controller;

class Database extends Controller
{
    public function __call($function, $parameters)
    {
        dd($function);
    }
}
