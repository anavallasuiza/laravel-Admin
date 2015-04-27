<?php

namespace Admin\Models;

use Illuminate\Database\Eloquent\Model as LModel;

class Model extends LModel
{
    use ModelTrait;

    protected $guarded = ['id'];
}
