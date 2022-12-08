<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentAttendance extends Model
{
    use HasFactory;
    protected $table = 'student_attendances';
    protected $primaryKey = 'student_attendance_id';

    public static function getStudentsByAttendanceId($attendance_id)
    {
      return  DB::table('student_attendances')
                  ->join('students', 'students.student_id', '=', 'student_attendances.student_id' )
                  ->join('users', 'users.id', '=', 'students.student_user_id' )
                  ->join('sections', 'sections.section_id', '=', 'students.section_id')
                  ->select(
                    'students.*', 
                    'sections.*', 
                    'users.name', 
                    'users.email',
                    'student_attendances.*'
                    )
                  ->where('student_attendances.attendance_id', $attendance_id)
                  ->orderBy('admission_no', 'asc')
                  ->get();
    }
}