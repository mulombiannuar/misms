<?php

namespace App\Models\Academic;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Score extends Model
{
    use HasFactory;
    protected $table = 'scores';
    protected $primaryKey = 'score_id';

    private function getSchoolSubjects()
    {
       return DB::table('subjects')->select('*')->orderBy('subject_name', 'asc')->get()->all();
    }

    private function getStudentSubjectsCombination($student_id)
    {
       return DB::table('student_subjects')
                 ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.subject_id')
                 ->where('student_id', $student_id)
                 ->select('*')
                 ->get();
    }

    public static function getStudentsScoresByExamRecord($record_id)
    {
        $subjectGrading = new SubjectGrading();
        $scores = DB::table('scores')
                    ->where('exam_record_id', $record_id)
                    ->join('students', 'students.student_id', '=', 'scores.student_id' )
                    ->join('users', 'users.id', '=', 'students.student_user_id' )
                    ->select('students.admission_no', 'scores.*', 'users.name')
                    ->orderBy('admission_no', 'asc')
                    ->get();

        for ($score=0; $score <count($scores) ; $score++) 
        { 
            $scores[$score]->grade = $subjectGrading->getSubjectGrading($scores[$score]->score, $scores[$score]->subject_id, $scores[$score]->class_numeric);
        }
        return $scores;
    }

    public static function getScoresById($id)
    {
        return  DB::table('scores')
                  ->where('score_id', $id)
                  ->join('students', 'students.student_id', '=', 'scores.student_id' )
                  ->join('users', 'users.id', '=', 'students.student_user_id' )
                  ->select('students.admission_no', 'scores.*', 'users.name')
                  ->first();
    }

   //Get section single exam results
   public function fetchSectionsStudentsSingleExamResults($exam_id, $section_numeric)
   {
      $sections = Section::getSectionsByClassNumeric($section_numeric);
      for ($s=0; $s <count($sections) ; $s++) { 
          $sections[$s]->students = $this->getStudentsScoresBySectionId($sections[$s]->section_id, $exam_id);
         //$sections[$s]->gradesData = $this->getGradeDistribution($sections[$s]->students);
         //$sections[$s]->subjects = $this->getSubjectsAnalysis($sections[$s]->students, $form_numeric);
      }
      return $sections;
   }

   private function getStudentsScoresBySectionId($section_id, $exam_id)
   {
       $students = Student::getStudentsBySectionId($section_id);
       for ($s=0; $s <count($students) ; $s++) { 
        # code...
       }
       return $students;
   }

}
