<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassAttendance extends Model
{
    use HasFactory;
    protected $table = 'class_attendances';
    protected $primaryKey = 'attendance_id';

    public static function getClassAttendanceById($id)
    {
         return DB::table('class_attendances')
                  ->join('sections', 'sections.section_id', '=', 'class_attendances.section_id')
                  ->where('attendance_id', $id)->first();
    }

    public static function getClassAttendances()
    {
         return DB::table('class_attendances')
                  ->join('sessions', 'sessions.session_id', '=', 'class_attendances.session_id')
                  ->join('sections', 'sections.section_id', '=', 'class_attendances.section_id')
                  ->join('users', 'users.id', '=', 'class_attendances.created_by')
                  ->select('class_attendances.*', 'sessions.*', 'sections.*', 'users.name as teacher_name')
                  ->orderBy('attendance_id', 'desc')
                  ->get();
    }

    public static function getClassAttendancesByUserId($user_id)
    {
         return DB::table('class_attendances')
                  ->join('sections', 'sections.section_id', '=', 'class_attendances.section_id')
                  ->join('users', 'users.id', '=', 'class_attendances.created_by')
                  ->select('class_attendances.*', 'sections.*', 'users.name as officer_name')
                  ->orderBy('attendance_id', 'desc')
                  ->where('created_by', $user_id)
                  ->get();
    }

    public static function getClassAttendanceBySectionAndDate($section_id, $date)
    {
       return DB::table('class_attendances')
                ->where(['section_id' => $section_id, 'date' => $date])
                ->first();
    }
}