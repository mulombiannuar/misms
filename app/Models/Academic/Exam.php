<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'exams';
    protected $primaryKey = 'exam_id';

    public static function getExams()
    {
        return Exam::orderBy('name', 'asc')
                   ->join('forms', 'forms.form_numeric', '=', 'exams.class_numeric')
                   ->get();
    }

    
    public static function getSingleTermExams($class_numeric, $year, $term)
    {
        return Exam::orderBy('name', 'asc')
                   ->join('forms', 'forms.form_numeric', '=', 'exams.class_numeric')
                   ->select('exam_id','term','name', 'class_numeric', 'converted', 'conversion', 'year')
                   ->where([
                    'class_numeric' => $class_numeric,
                    'year' => $year,
                    'term' => $term
                   ])
                   ->get()->toArray();
    }
}