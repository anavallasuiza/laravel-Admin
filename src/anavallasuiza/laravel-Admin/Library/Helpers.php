<?php namespace Admin\Library;

class Helpers {
    public static function slug($string)
    {
        $string = preg_replace('/[^\p{L}0-9]/u', '-', trim(strip_tags($string)));
        $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
        $string = preg_replace('/&(\w)\w+;/', '$1', $string);
        $string = preg_replace(['/\W/', '/\-+/'], '-', $string);
        $string = preg_replace('/^\-|\-$/', '', $string);

        return strtolower($string);
    }
}