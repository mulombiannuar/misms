<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentSubject extends Model
{
    use HasFactory;
    protected $table = 'student_subjects';
    protected $primaryKey = 'ss_id';

    public function getStudentSubjects($student_id)
    {
        return $this->select('subjects.*')
                    ->where('student_id', $student_id)
                    ->join('subjects', 'subjects.subject_id', '=', 'student_subjects.subject_id')
                    ->get();
    }

    public static function getSubjectStudentsBySectionId($section_id, $subject_id)
    {
        return DB::table('student_subjects')
                 ->join('users', 'users.id', '=', 'student_subjects.student_id')
                 ->join('students', 'students.student_user_id', '=', 'users.id')
                  ->select(
                    'users.name', 
                    'students.student_id',
                    'students.admission_no'
                    )
                  ->where([
                    'students.section_id' => $section_id,
                    'student_subjects.subject_id' => $subject_id
                    ])
                  ->get();
    }

    public static function getSectionSubjects($section_id)
    {
        return DB::table('student_subjects')
                 ->join('subjects', 'subjects.subject_id', '=', 'student_subjects.subject_id')
                 ->join('users', 'users.id', '=', 'student_subjects.student_id')
                 ->join('students', 'students.student_user_id', '=', 'users.id')
                 ->where('section_id', $section_id)
                 ->distinct('subject_id')
                 ->select(
                  'student_subjects.subject_id', 
                  'subject_name', 
                  )
                 ->get();
    }
}