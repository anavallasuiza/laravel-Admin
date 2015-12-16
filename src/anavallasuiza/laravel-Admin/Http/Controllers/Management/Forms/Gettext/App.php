<?php
namespace Admin\Http\Controllers\Management\Forms\Gettext;

use FormManager\Builder as B;
use Eusonlito\LaravelFormManager\Form as F;

class App extends F
{
    public function __construct()
    {
        return $this->method('post')->add([
            '_processor' => B::hidden()->value('app'),
        ]);
    }
}
