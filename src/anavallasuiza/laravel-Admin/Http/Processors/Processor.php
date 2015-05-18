<?php
namespace Admin\Http\Processors;

use Laravel\Processor\Processors\ProcessorTrait;
use Admin\Library;
use Auth;
use Session;

abstract class Processor
{
    use ProcessorTrait;

    protected $user;
    protected $locale;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->locale = Library\Helpers::locale();
    }
}
