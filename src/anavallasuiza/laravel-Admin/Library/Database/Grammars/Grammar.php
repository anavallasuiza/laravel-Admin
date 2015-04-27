<?php

namespace Admin\Library\Database\Grammars;

use ReflectionClass;
use Schema;

class Grammar
{
    private static $connection;

    public static function getConnection()
    {
        if (self::$connection) {
            return self::$connection;
        }

        $connection = Schema::getConnection();

        $grammar = $connection->getSchemaGrammar();
        $grammar = (new ReflectionClass($grammar))->getShortName();
        $grammar = __NAMESPACE__.'\\'.$grammar;

        $grammar = $connection->withTablePrefix(new $grammar());

        $connection->setSchemaGrammar($grammar);

        return self::$connection = $connection;
    }
}
