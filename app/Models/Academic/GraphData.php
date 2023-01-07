<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraphData extends Model
{
    use HasFactory;
    protected $table = 'graph_data';

    protected $fillable = [
        'exam_id', 
        'section_id', 
        'subjects',
        'mean_scores', 
    ];

    protected $casts = [
        'subjects' => 'array',
        'subjects' => 'array'
    ];
}
