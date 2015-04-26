<?php namespace Admin\Http\Processors;

use Laravel\Processor\Processors\ProcessorTrait;

use ErrorException;
use Auth;
use Input;
use Request;
use Session;

abstract class Processor
{
    use ProcessorTrait;

    protected $user;
    protected $locale;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->locale = Session::get('locale');
    }
}
