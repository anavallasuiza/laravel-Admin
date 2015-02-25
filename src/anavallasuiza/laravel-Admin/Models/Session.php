<?php namespace Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model {
    protected $table = 'admin_sessions';
    protected $guarded = ['id'];

    public $timestamps = false;
}
