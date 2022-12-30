<?php

namespace App\Models\Academic;

use App\Models\Admin\DefaultGrade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGrading extends Model
{
    use HasFactory;
    protected $table = 'subject_gradings';
    protected $primaryKey = 'grade_id';

    /*Get subject grading for a form*/
    public function getSubjectGradingForForm($subject_id, $form_numeric)
    {
        $grades = $this::where([
            'subject_id' => $subject_id,
            'form_numeric' => $form_numeric
        ])->get();

        if($grades->count() == 0){
            $grades = DefaultGrade::all();
        }
        return $grades;
    }

    public function getSubjectGrading($score, $subject_id, $form_numeric)
    {
         $grade = $this::select('grade_name', 'score_remarks')
                       ->where(['subject_id' => $subject_id,'form_numeric' => $form_numeric])
                       ->where('min_score', '<=', $score)
                       ->where('max_score', '>=', $score)
                       ->first();

        if(empty($grade)){
            $grade = [
                'grade_name' => $this->getScoreGrading($score),
                'score_remarks' => $this->getScoreRemarks($score),
            ];
        }
        return $grade;
    }

    

    ////Get default grading
    public function getScoreGrading($score)
    {
        $grade = '';
        if($score >= 80 && $score <= 100)
        {
        $grade = 'A';
        }
        if($score >= 75 && $score <80)
        {
        $grade = 'A-';
        }
        if($score >= 70 && $score <75)
        {
        $grade = 'B+';
        }
        if($score >= 65 && $score <70)
        {
        $grade = 'B';
        }
        if($score >= 60 && $score <65)
        {
        $grade = 'B-';
        }
        if($score >= 55 && $score <60)
        {
        $grade = 'C+';
        }
        if($score >= 50 && $score <55)
        {
        $grade = 'C';
        }
        if($score >= 45 && $score <50)
        {
        $grade = 'C-';
        }
        if($score >= 40 && $score <45)
        {
        $grade = 'D+';
        }
        if($score >= 35 && $score <40)
        {
        $grade = 'D';
        }
        if($score >= 30 && $score <35)
        {
        $grade = 'D-';
        }
        if($score >= 0 && $score <35)
        {
        $grade = 'E';
        }

        return $grade;
    }
      
    ////Get default grading
    public function getScoreRemarks($score)
    {
        $remarks = '';
        if($score >= 80 && $score <= 100)
        {
        $remarks = 'Excellent Work!';
        }
        if($score >= 75 && $score <80)
        {
        $remarks = 'Good Work';
        }
        if($score >= 70 && $score <75)
        {
        $remarks = 'Good work';
        }
        if($score >= 65 && $score <70)
        {
        $remarks = 'Good Trial';
        }
        if($score >= 60 && $score <65)
        {
        $remarks = 'Good Trial';
        }
        if($score >= 55 && $score <60)
        {
        $remarks = 'Average Work';
        }
        if($score >= 50 && $score <55)
        {
        $remarks = 'Average Work';
        }
        if($score >= 45 && $score <50)
        {
        $remarks = 'Can do better';
        }
        if($score >= 40 && $score <45)
        {
        $remarks = 'Can do better';
        }
        if($score >= 35 && $score <40)
        {
        $remarks = 'Work more harder';
        }
        if($score >= 30 && $score <35)
        {
        $remarks = 'Work more harder';
        }
        if($score >= 0 && $score <35)
        {
        $remarks = 'Work more harder';
        }

        return $remarks;
    }

    // Get subject mean
    public function getSubjectPoints($grade)
    {
    $points = 12;
    if($grade == "A")
    {
        $points = 12;
    }
    if($grade == "A-")
    {
        $points = 11;
    }
    if($grade == "B+")
    {
        $points = 10;
    }
    if($grade == "B")
    {
        $points = 9;
    }
    if($grade == "B-")
    {
        $points = 8;
    }
    if($grade == "C+")
    {
        $points = 7;
    }
    if($grade == "C")
    {
        $points = 6;
    }
    if($grade == "C-")
    {
        $points = 5;
    }
    if($grade == "D+")
    {
        $points = 4;
    }
    if($grade == "D")
    {
        $points = 3;
    }
    if($grade == "D-")
    {
        $points = 2;
    }
    if($grade == "E")
    {
        $points = 1;
    }
    return $points;
    }
  
    // get student mean grade by points
    public function getStudentMeanGrade($tpoints)
    {
        $grade = "-";
        if($tpoints >= 1)
        {
            $grade = "E";
        }
        if($tpoints >= 2)
        {
            $grade = "D-";
        }
        if($tpoints >= 3)
        {
            $grade = "D";
        }
        if($tpoints >= 4)
        {
            $grade = "D+";
        }
        if($tpoints >= 5)
        {
            $grade = "C-";
        }
        if($tpoints >= 6)
        {
            $grade = "C";
        }
        if($tpoints >= 7)
        {
            $grade = "C+";
        }
        if($tpoints >= 8)
        {
            $grade = "B-";
        }
        if($tpoints >= 9)
        {
            $grade = "B";
        }
        if($tpoints >= 10)
        {
            $grade = "B+";
        }
        if($tpoints >= 11)
        {
            $grade = "A-";
        }
        if($tpoints >= 12)
        {
            $grade = "A";
        }
        return $grade;
    }
}