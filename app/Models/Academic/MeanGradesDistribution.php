<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeanGradesDistribution extends Model
{
    use HasFactory;
    protected $table = 'mean_grades_distributions';

    protected $fillable = [
        'term', 
        'year', 
        'section_id', 
        'students_entry',
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
