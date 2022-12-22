<?php

namespace App\Models\Academic;

use App\Models\Student\Student;
use App\Utilities\Utilities;
use Carbon\Carbon;
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

    public static function getReportDateRanges($section_id, $start_date, $end_date)
    {
       $dates = ClassAttendance::getDBDateRangesBySectionId($section_id, $start_date, $end_date);
       
       if (empty($dates || !is_array($dates))) return ['error' => true, 'message' => 'We could not find records you have specified'];
       
       $formattedDates = Utilities::formatDates($dates);  
       $attendances = ClassAttendance::getDBAttendanceRangesBySectionId($section_id, $start_date, $end_date);
       
       $students = ClassAttendance::getSectionStudents($section_id);
       if(empty($students)) return ['error' => true, 'message' => 'The class you have selected does not contain any students'];
       
       // loop through attendances, loop through students
       for ($s=0; $s <count($students) ; $s++) 
       { 
          $students[$s]->attendances = ClassAttendance::getStudentAttendances($students[$s]->student_id, $attendances);
       }

       return [
          'error' => false,
          'dates' => $formattedDates,
          'students' =>  $students
       ]; 
    }

    private function getDBDateRangesBySectionId($section_id, $start_date, $end_date)
    {
       $startDate = Utilities::setDate($start_date);
       $endDate = Utilities::setDate($end_date);  
       return DB::table('class_attendances')
                ->where('section_id', $section_id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->pluck('date')
                ->toArray();
      
    }

    private function getDBAttendanceRangesBySectionId($section_id, $start_date, $end_date)
    {
       $startDate = Utilities::setDate($start_date);
       $endDate = Utilities::setDate($end_date);  
       return DB::table('class_attendances')
                ->where('section_id', $section_id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();
    }

    private function getStudentAttendances(int $student_id, $attendances)
    {
      $studentAttendances = [];
      for ($s=0; $s <count($attendances) ; $s++) { 
         $attendance =  DB::table('student_attendances')
                          ->select('attendance_status', 'comment')
                          ->where([
                           'student_id' => $student_id, 
                           'attendance_id' => $attendances[$s]->attendance_id])
                         ->first();
         array_push($studentAttendances, $attendance);
      }
      return $studentAttendances;
    }

    public static function getSectionStudents($section_id)
    {
        return  DB::table('students')
                  ->where([
                    'students.section_id' => $section_id,
                    'users.deleted_at' => null, 
                   ])
                  ->join('users', 'users.id', '=', 'students.student_user_id' )
                  ->join('sections', 'sections.section_id', '=', 'students.section_id')
                  ->select('students.admission_no', 'students.student_id', 'users.name')
                  ->orderBy('admission_no', 'asc')
                  ->get();
    }
}