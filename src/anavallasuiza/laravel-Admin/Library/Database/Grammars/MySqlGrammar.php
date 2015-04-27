<?php

namespace Admin\Library\Database\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as Base;
use Admin\Library\Database\Formats;

class MySqlGrammar extends Base
{
    public function typeEmail()
    {
        return Formats\Email::MySqlGrammar();
    }
}
