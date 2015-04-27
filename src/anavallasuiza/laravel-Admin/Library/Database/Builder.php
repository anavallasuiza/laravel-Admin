<?php

namespace Admin\Library\Database;

use Closure;
use Illuminate\Database\Schema\Builder as LBuilder;

class Builder extends LBuilder
{
    public function createBlueprint($table, Closure $callback = null)
    {
        return parent::createBlueprint($table, $callback);
    }
}
