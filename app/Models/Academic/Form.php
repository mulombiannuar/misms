<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'forms';
    protected $primaryKey = 'form_id';

     //Get grading for a particular subject
     public function subjectGrades($subject_id)
     {
         $grades = [['grade' =>'A'], ['grade' =>'A-'], ['grade' =>'B+'], ['grade' =>'B'], ['grade' =>'B-'], ['grade' =>'C+'], ['grade' =>'C'], ['grade' =>'C-'], ['grade' =>'D+'], ['grade' =>'D'], ['grade' =>'D-'], ['grade' =>'E']];
         $gradings = [];
         
         $gradings = SubjectGrading::where([
                    'subject_id' => $subject_id,
                    'form_numeric' => $this->form_numeric
                ])->get();
         
         return $gradings;
     }
 
}