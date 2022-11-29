<?php

namespace App\Models\Hostel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRoom extends Model
{
    use HasFactory;
    protected $table = 'student_rooms';
    protected $primaryKey = 'student_room_id';
}