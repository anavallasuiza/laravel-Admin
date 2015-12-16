<?php
namespace Admin\Library;

class Helpers
{
    public static function camelcase($string)
    {
        return ucfirst(preg_replace_callback('/\-([a-z])/', function ($matches) {
            return ucfirst($matches[1]);
        }, $string));
    }

    public static function locale()
    {
        $cookie = config('gettext.cookie');

        return isset($_COOKIE[$cookie]) ? $_COOKIE[$cookie] : config('gettext.locales')[0];
    }
}
