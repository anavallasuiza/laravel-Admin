<?php

namespace Admin\Library\Database;

use Closure;
use App;

class Schema
{
    private static $builder;
    private static $tables = [];

    private static function builder()
    {
        if (self::$builder) {
            return self::$builder;
        }

        return self::$builder = new Builder(App::make('db')->connection());
    }

    public static function define($table, Closure $callback)
    {
        $blueprint = self::builder()->createBlueprint($table);

        $blueprint->create();

        $callback($blueprint);

        return self::$tables[$table] = $blueprint;
    }

    public static function getTables()
    {
        return self::$tables;
    }
}
