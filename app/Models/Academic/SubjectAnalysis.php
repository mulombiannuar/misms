<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAnalysis extends Model
{
    use HasFactory;
    protected $table = 'subjects_analysis';

    protected $fillable = [
        'exam_id', 
        'section_id', 
        'subject_id', 
        'total_students', 
        'total_points', 
        'average_points', 
        'average_grade',
        'class_position',
        'total_marks',
        'average_marks',
        'grades', 
    ];

    protected $casts = [
        'grades' => 'array'
    ];
}
