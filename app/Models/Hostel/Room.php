<?php

namespace App\Models\Hostel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';


    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'hostel_id');
    }

    public static function getRooms()
    {
       return DB::table('rooms')
                ->join('hostels', 'hostels.hostel_id', '=', 'rooms.hostel_id')
                // ->join('users', 'users.id', '=', 'hostels.hostel_master')
                ->select('rooms.*','hostels.*')
                ->where('rooms.deleted_at', null)
                ->orderBy('room_label', 'asc')
                ->get();
    }

    public static function getRoomsByHostelId($hostel_id)
    {
       return DB::table('rooms')
                ->join('hostels', 'hostels.hostel_id', '=', 'rooms.hostel_id')
                // ->join('users', 'users.id', '=', 'hostels.hostel_master')
                ->select('rooms.*','hostels.*')
                ->where(['rooms.deleted_at'=> null, 'rooms.hostel_id' => $hostel_id])
                ->orderBy('room_label', 'asc')
                ->get();
    }

    public static function getRoomById($id)
    {
       return DB::table('rooms')
                ->join('hostels', 'hostels.hostel_id', '=', 'rooms.hostel_id')
                ->leftJoin('users', 'users.id', '=', 'hostels.hostel_master')
                ->select('rooms.*','hostels.*', 'users.name')
                ->where(['rooms.deleted_at' => null, 'room_id' => $id])
                ->first();
    }

    public static function getStudentsRoomById($id)
    {
      return StudentRoom::where('student_rooms.room_id', $id)
                        ->join('students', 'students.student_id', '=', 'student_rooms.student_id')
                        ->join('bed_spaces', 'bed_spaces.bed_id', '=', 'student_rooms.bed_id')
                        ->join('users', 'users.id', '=', 'students.student_user_id')
                        ->orderBy('users.name', 'asc')
                        ->get();
    }
}