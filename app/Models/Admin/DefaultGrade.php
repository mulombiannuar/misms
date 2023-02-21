<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultGrade extends Model
{
    use HasFactory;
    protected $table = 'default_grades';
    protected $primaryKey = 'grade_id';

    public function getGrades()
    {
        if($this->get()){
            return $this->get();
        }
        return $this->defaultGrades();;
    }
    
    public function getDefaultGrades()
    {
        // if($this->get()){
        //     return $this->get();
        // }
        return $this->defaultGrades();;
    }

    public function defaultGrades()
    {
        return [
            [
                "grade_id" => 1, 
                "grade_name" => "A", 
                "min_score" => 80, 
                "max_score" => 100, 
                "score_remarks" => "Excellent work, keep it up"
            ],
            [
                "grade_id" => 2, 
                "grade_name" => "A-", 
                "min_score" => 75, 
                "max_score" => 79, 
                "score_remarks" => "Good work, keep it up"
            ],
            [
                "grade_id" => 3, 
                "grade_name" => "B+", 
                "min_score" => 70, 
                "max_score" => 74.9, 
                "score_remarks" => "Excellent work, keep it up"
            ],
            [
                "grade_id" => 4, 
                "grade_name" => "B", 
                "min_score" => 65, 
                "max_score" => 69.9, 
                "score_remarks" => "You can do better than this"
            ],
            [
                "grade_id" => 5, 
                "grade_name" => "B-", 
                "min_score" => 60, 
                "max_score" => 64.9, 
                "score_remarks" => "You can do better than this"
            ],
            [
                "grade_id" => 6, 
                "grade_name" => "C+", 
                "min_score" => 55, 
                "max_score" => 59.9, 
                "score_remarks" => "You can do better than this"
            ],
            [
                "grade_id" => 7, 
                "grade_name" => "C", 
                "min_score" => 50, 
                "max_score" => 54.9, 
                "score_remarks" => "Put more effort. Read hard"
            ],
            [
                "grade_id" => 8, 
                "grade_name" => "C-", 
                "min_score" => 45, 
                "max_score" => 49.9, 
                "score_remarks" => "Put more effort. Read hard"
            ],
            [
                "grade_id" => 9, 
                "grade_name" => "D+", 
                "min_score" => 40, 
                "max_score" => 44.9, 
                "score_remarks" => "Put more effort. Read hard"
            ],
            [
                "grade_id" => 10, 
                "grade_name" => "D", 
                "min_score" => 35, 
                "max_score" => 39.9, 
                "score_remarks" => "Put more effort. Read hard"
            ],
            [
                "grade_id" => 11, 
                "grade_name" => "D-", 
                "min_score" => 30, 
                "max_score" => 34.9, 
                "score_remarks" => "Put more effort. Read hard"
            ],
            [
                "grade_id" => 12, 
                "grade_name" => "E", 
                "min_score" => 0, 
                "max_score" => 29.9, 
                "score_remarks" => "Put more effort. Read hard"
            ],
           
        ];
    }
}