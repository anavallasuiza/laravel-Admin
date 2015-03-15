<?php
function query($key, $value = null)
{
    parse_str(parse_url(getenv('REQUEST_URI'), PHP_URL_QUERY), $query);

    if (is_array($key)) {
        $query = array_merge($query, $key);
    } else {
        $query[$key] = $value;
    }

    foreach ($query as $key => $value) {
        if (strlen($value) === 0) {
            unset($query[$key]);
        }
    }

    return '?'.http_build_query($query);
}

function slug($string)
{
    $string = preg_replace('/[^\p{L}0-9]/u', '-', trim(strip_tags($string)));
    $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace('/&(\w)\w+;/', '$1', $string);
    $string = preg_replace(['/\W/', '/\-+/'], '-', $string);
    $string = preg_replace('/^\-|\-$/', '', $string);

    return strtolower($string);
}

function token()
{
    return (new Admin\Http\Controllers\Forms\Form())->token();
}
