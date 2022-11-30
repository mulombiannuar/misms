<?php

namespace App\Models\Hostel;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BedSpace extends Model
{
    use HasFactory;
    protected $table = 'bed_spaces';
    protected $primaryKey = 'bed_id';

    public static function getBedSpaces()
    {
       $spaces = DB::table('bed_spaces')
                  ->join('hostels', 'hostels.hostel_id', '=', 'bed_spaces.hostel_id')
                  ->join('rooms', 'rooms.room_id', '=', 'bed_spaces.room_id')
                  ->select('bed_spaces.space_label', 'rooms.room_label', 'hostels.hostel_name')
                  ->orderBy('space_label', 'asc')
                  ->get();
        for ($s=0; $s <count($spaces) ; $s++) 
        { 
            $spaces[$s]->student_no = $s + 1;
            $spaces[$s]->student_name = 'Caleb Hamisi';
            $spaces[$s]->student_admn = 'MIT/20/2020';
        }
        return $spaces;
    }

    public static function getBedSpacesByRoomId($room_id)
    {
       $spaces = BedSpace::where('bed_spaces.room_id', $room_id)
                         ->join('rooms', 'rooms.room_id', '=', 'bed_spaces.room_id')
                         ->orderBy('space_label', 'asc')
                         ->get();
        for ($s=0; $s <count($spaces) ; $s++) 
        { 
            $spaces[$s]->student = 'MIT2020@mulan.co.ke';
        }
        return $spaces;
    }

    public function getBedSpaceStudent($bed_id)
    {
        //
    }
}