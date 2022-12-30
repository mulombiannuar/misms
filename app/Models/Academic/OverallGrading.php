<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class OverallGrading extends Model
{
    use HasFactory;
    protected $table = 'overall_gradings';
    protected $primaryKey = 'grade_id';

      /**Get class teacher and principal comment */
      public function comments($score, $class_numeric)
      {
         // return $score;
         $gradeData = new stdClass;
         $grades = DB::table('overall_gradings')
                  ->get();
         for ($s=0; $s <count($grades) ; $s++) 
         { 
            if ($grades[$s]->form_numeric == $class_numeric 
               && $score >= $grades[$s]->min_score 
               && $score <= $grades[$s]->max_score) {
               return $grades[$s];
            }
            else {
               $gradeData->score_remarks = 'You can still do more better than this because you have the potential. Please do a lot of revision.';
               $gradeData->principal_remarks = 'You are encouraged to continue working extra hard to yield better results than this';
               return $gradeData;
            }
         }
      }

      public function getOverallGrading($score, $form_numeric)
      {
         $grade = $this::select('grade_name', 'teacher_remarks', 'principal_remarks')
                        ->where(['form_numeric' => $form_numeric])
                        ->where('min_score', '<=', $score)
                        ->where('max_score', '>=', $score)
                        ->first();

         if(empty($grade)){
            $subjectGrading = new SubjectGrading();
            $grade = [
                  'grade_name' => $subjectGrading->getScoreGrading($score),
                  'teacher_remarks' => $subjectGrading->getScoreRemarks($score),
                  'principal_remarks' => $subjectGrading->getScoreRemarks($score),
            ];
         }
         return $grade;
      }
}