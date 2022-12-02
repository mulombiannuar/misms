<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    use HasFactory;
    protected $table = 'student_subjects';
    protected $primaryKey = 'ss_id';

    public function getStudentSubjects($student_id)
    {
        return $this->select('subjects.*')
                    ->where('student_id', $student_id)
                    ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.subject_id')
                    ->get();
    }
}