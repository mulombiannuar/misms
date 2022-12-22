<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubmittedScore extends Model
{
    use HasFactory;
    protected $table = 'submitted_scores';
    protected $primaryKey = 'subm_id';

    public static function getSubmittedScoreById($id)
    {
         return DB::table('submitted_scores')
                  ->join('subjects', 'subjects.subject_id', '=', 'submitted_scores.subject_id')
                  ->join('exams', 'exams.exam_id', '=', 'submitted_scores.exam_id')
                  ->join('sections', 'sections.section_id', '=', 'submitted_scores.section_id')
                  ->join('users', 'users.id', '=', 'submitted_scores.teacher_id')
                  ->select(
                    'submitted_scores.*',
                    'users.name',
                    'exams.name as exam_name',
                    'sections.section_name',
                    'sections.section_numeric', 
                    'subjects.subject_name'
                    )
                  ->where('subm_id', $id)
                  ->first();
    }

    public static function getSubmittedScores()
    {
         $scores = SubmittedScore::submittedScores();
         for ($s=0; $s <count($scores) ; $s++) { 
             $scores[$s]->count = DB::table('scores')->where('exam_record_id', $scores[$s]->subm_id)->count();
         }
         return $scores;
    }

    private function submittedScores()
    {
       return DB::table('submitted_scores')
                  ->join('subjects', 'subjects.subject_id', '=', 'submitted_scores.subject_id')
                  ->join('exams', 'exams.exam_id', '=', 'submitted_scores.exam_id')
                  ->join('sections', 'sections.section_id', '=', 'submitted_scores.section_id')
                  ->join('users', 'users.id', '=', 'submitted_scores.teacher_id')
                  ->select(
                    'submitted_scores.*',
                    'users.name',
                    'exams.name as exam_name',
                    'sections.section_name',
                    'sections.section_numeric', 
                    'subjects.subject_name'
                    )
                  ->orderBy('submitted_scores.subm_id', 'desc')
                  ->get();
    }
}
