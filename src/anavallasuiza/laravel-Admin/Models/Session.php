<?php
namespace Admin\Models;

class Session extends Model
{
    protected $table = 'admin_sessions';
    protected $guarded = ['id'];

    public $timestamps = false;
}
