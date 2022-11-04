<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $primaryKey = 'log_id';

    public static function getLogs()
    {
        return DB::table('logs')
                  ->join('users', 'users.id', '=', 'logs.user_id')
                  ->select('logs.*', 'users.name', 'users.email')
                  ->orderBy('log_id', 'desc')
                  ->get();
    }
}