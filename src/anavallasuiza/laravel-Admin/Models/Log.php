<?php namespace Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {
    protected $table = 'admin_logs';
    protected $guarded = ['id'];

    public $timestamps = false;
}
