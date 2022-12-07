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
}