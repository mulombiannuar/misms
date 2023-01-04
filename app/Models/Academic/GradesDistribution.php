<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradesDistribution extends Model
{
    use HasFactory;
    protected $table = 'grades_distribution';

    protected $fillable = [
        'exam_id', 
        'section_id', 
        'total_students', 
        'total_points', 
        'average_points', 
        'average_grade',
        'grades', 
    ];

    protected $casts = [
        'grades' => 'array'
    ];
}
