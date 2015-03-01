<?php namespace Admin\Models;

class Log extends Model {
    protected $table = 'admin_logs';
    protected $guarded = ['id'];

    public $timestamps = false;
}
