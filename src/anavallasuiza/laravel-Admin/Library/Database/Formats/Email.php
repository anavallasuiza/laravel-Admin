<?php

namespace Admin\Library\Database\Formats;

class Email
{
    private static $field = 'email';

    public static function field()
    {
        return self::$field;
    }

    public static function MySqlGrammar()
    {
        return 'varchar(255)';
    }
}
