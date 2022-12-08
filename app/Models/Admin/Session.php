<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Session extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sessions';
    protected $primaryKey = 'session_id';

    protected $fillable = ['term', 'session', 'opening_date', 'closing_date'];

    public static function getSessions()
    {
        return DB::table('sessions')->orderBy('term','asc')->get();
    }

    public static function getActiveSession()
    {
        return DB::table('sessions')->where('status',1)->first();
    }
}