<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use stdClass;

class Session extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sessions';
    protected $primaryKey = 'session_id';

    protected $fillable = ['term', 'session', 'opening_date', 'closing_date'];

    public static function getSessions()
    {
        return Session::orderBy('session_id','asc')->get();
    }

    public static function getActiveSession()
    {
        return Session::where('status', 1)->first();
    }

    public static function getClosingAndOpeningDates($current_sesion_id)
    {
        $dates = new stdClass;
        $currentSession = Session::find($current_sesion_id);
        $currentTerm = $currentSession->term;
        
        $nextTerm = 1;
        $nextSession = $currentSession->session;

        if ($currentTerm == 1 || $currentTerm == 2) {
            $nextTerm = $currentTerm + 1;
            $nextSession = $currentSession->session;
        }

        if ($currentTerm == 3) {
            $nextTerm = 1;
            $nextSession = $currentSession->session + 1;
        }

        $closingDate = Session::select('closing_date')->where('session_id', $current_sesion_id)->first();
        $openingDate = Session::select('opening_date')->where(['term' => $nextTerm, 'session' => $nextSession])->first();

        $closing_date = empty($closingDate) ?  '00,0 0 0 0' : $closingDate->closing_date;
        $opening_date = empty($openingDate) ?  '00,0 0 0 0' : $openingDate->opening_date;

        $dates->closingDate = $closing_date;
        $dates->openingDate = $opening_date;
        return $dates;
    }
}