<?php
namespace Admin\Library;

class Helpers
{
    public static function slug($string)
    {
        $string = preg_replace('/[^\p{L}0-9]/u', '-', trim(strip_tags($string)));
        $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
        $string = preg_replace('/&(\w)\w+;/', '$1', $string);
        $string = preg_replace(['/\W/', '/\-+/'], '-', $string);
        $string = preg_replace('/^\-|\-$/', '', $string);

        return strtolower($string);
    }

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
