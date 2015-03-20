<?php namespace Admin\Library\Database\Formats;

use Illuminate\Database\Schema\Blueprint;

class Format extends Blueprint
{
    public function email($column)
    {
        return $this->addColumn(Email::field(), $column);
    }

    public function grammar($format)
    {
        return $format::getGrammar();
    }
}