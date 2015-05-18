<?php
namespace Admin\Http\Controllers\Management\Forms\Gettext;

use FormManager\Builder as B;
use Eusonlito\LaravelFormManager\Form as F;

class Admin extends F
{
    public function __construct()
    {
        return $this->method('post')->add([
            '_processor' => B::hidden()->value('admin'),
        ]);
    }
}
