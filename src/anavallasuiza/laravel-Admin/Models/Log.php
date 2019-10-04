<?php
namespace Admin\Models;

use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    protected $table = 'admin_logs';
    protected $guarded = ['id'];

    public $timestamps = false;

    public static function register($action, $table, $row)
    {
        self::insert(self::getData($action, $table, $row));
    }

    private static function getData($action, $table, $row)
    {
        $user = Auth::user();

        $data = [
            'created_at' => date('Y-m-d H:i:s'),
            'related_table' => $table,
            'action' => $action,
            'admin_users_id' => ($user ? $user->id : 0),
        ];

        if (isset($row->id)) {
            $data['related_id'] = $row->id;
        }

        return $data;
    }
}
