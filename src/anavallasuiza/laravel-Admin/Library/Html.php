<?php
namespace Admin\Library;

use Packer;

class Html
{
    public static function fix($html)
    {
        if (empty($html)) {
            return $html;
        }

        $html = '<p>'.self::xss($html).'</p>';

        $valid = 'class|src|target|alt|title|href|rel';

        $html = preg_replace('#<(font|span) style="font-weight[^"]+">([^<]+)</(font|span)>#i', '<strong>$2</strong>', $html);
        $html = preg_replace('#<(font|span) style="font-style:\s*italic[^"]+">([^<]+)</(font|span)>#i', '<i>$2</i>', $html);

        $html = preg_replace('# ('.$valid.')=#i', ' |$1|', $html);
        $html = preg_replace('# [a-z]+=["\'][^"\']*["\']#i', '', $html);
        $html = preg_replace('#\|('.$valid.')\|#i', ' $1=', $html);
        $html = preg_replace('#</?(font|span)[^>]*>#', '', $html);
        $html = preg_replace('#<(/?)div#', '<$1p', $html);
        $html = preg_replace('#<(/?)b>#', '<$1strong>', $html);
        $html = preg_replace('#<br\s*/?>#', '</p><p>', $html);

        libxml_use_internal_errors(true);

        $DOM = new \DOMDocument();
        $DOM->recover = true;
        $DOM->preserveWhiteSpace = false;
        $DOM->substituteEntities = false;
        $DOM->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOBLANKS | LIBXML_ERR_NONE);
        $DOM->encoding = 'utf-8';

        $html = $DOM->saveHTML();

        libxml_use_internal_errors(false);

        $html = preg_replace('~<(?:!DOCTYPE|/?(?:\?xml|html|head|body))[^>]*>\s*~i', '', $html);
        $html = preg_replace('/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU', '', $html);

        return trim(str_replace('&nbsp;', ' ', $html));
    }

    public static function xss($html)
    {
        // Fix &entity\n;
        $html = str_replace(['&amp;', '&lt;', '&gt;'], ['&amp;amp;', '&amp;lt;', '&amp;gt;'], $html);
        $html = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $html);
        $html = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $html);
        $html = html_entity_decode($html, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $html = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $html);

        // Remove javascript: and vbscript: protocols
        $html = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $html);
        $html = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $html);
        $html = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $html);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $html = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $html);
        $html = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $html);
        $html = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $html);

        // Remove namespaced elements (we do not need them)
        $html = preg_replace('#</*\w+:\w[^>]*+>#i', '', $html);

        do {
            // Remove really unwanted tags
            $old_data = $html;
            $html = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $html);
        } while ($old_data !== $html);

        return $html;
    }

    public static function elixir($file)
    {
        static $manifest = null;

        $base = 'assets/admin/build';

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path($base).'/rev-manifest.json'), true);
        }

        if (!isset($manifest[$file])) {
            throw new InvalidArgumentException(__('File %s not defined in asset manifest.', $file));
        }

        return asset($base.'/'.$manifest[$file]);
    }

    public static function query($key, $value = null)
    {
        parse_str(parse_url(getenv('REQUEST_URI'), PHP_URL_QUERY), $query);

        if (is_array($key)) {
            $query = array_merge($query, $key);
        } else {
            $query[$key] = $value;
        }

        foreach ($query as $key => $value) {
            if (is_string($value) && (strlen($value) === 0)) {
                unset($query[$key]);
            } elseif (is_array($value) && !($value = array_filter($value))) {
                unset($query[$key]);
            }
        }

        return '?'.http_build_query($query);
    }

    public static function DT($string)
    {
        if (strpos($string, 'datatables.s') !== 0) {
            return $string;
        } elseif (strstr($string, 'sLengthMenu')) {
            return '_MENU_';
        } else  {
            return str_replace('datatables.s', '', $string);
        }
    }

    public static function img($image, $transform = '')
    {
        if (is_object($image)) {
            $image = $image->image;
        }

        if (strpos($image, '/') !== 0) {
            $image = '/storage/resources/'.$image;
        }

        if (empty($transform)) {
            return asset($image);
        }

        return Packer::img($image, $transform);
    }
}
