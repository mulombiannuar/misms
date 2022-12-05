<?php

namespace App\Models\Hostel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRoom extends Model
{
    use HasFactory;
    protected $table = 'student_rooms';
    protected $primaryKey = 'student_room_id';

    public static function getStudentRoom($student_id)
    {
        return StudentRoom::where('student_rooms.student_id', $student_id)
                          ->join('hostels', 'hostels.hostel_id', '=', 'student_rooms.hostel_id')
                          ->join('rooms', 'rooms.room_id', '=', 'student_rooms.room_id')
                          ->join('bed_spaces', 'bed_spaces.bed_id', '=', 'student_rooms.bed_id')
                          ->first();
    }
}