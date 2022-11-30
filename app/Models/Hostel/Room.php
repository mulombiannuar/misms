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
       return [];
    }
}