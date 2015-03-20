<?php namespace Admin\Library\Database;

use Closure;
use Schema;
use Admin\Library\Database\Formats\Format;
use Admin\Library\Database\Grammars\Grammar;

class Migration
{
    private static $connection;
    private static $tables = [];

    public function table($table, Closure $callback)
    {
        return self::$tables[$table] = self::getFormat($table, $callback);
    }

    public function toSql()
    {
        $connection = self::getConnection();
        $grammar = $connection->getSchemaGrammar();

        $sql = [];

        foreach (self::$tables as $format) {
            $sql = array_merge($sql, $format->toSql($connection, $grammar));
        }

        return array_map(function($row) {
            return $row.';';
        }, $sql);
    }

    private static function getConnection()
    {
        if (self::$connection) {
            return self::$connection;
        }

        return self::$connection = Grammar::getConnection();
    }

    private static function getFormat($table, Closure $callback)
    {
        $format = new Format($table);
        $format->create();

        $callback($format);

        return $format;
    }
}