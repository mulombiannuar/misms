<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeanGraphData extends Model
{
    use HasFactory;
    protected $table = 'mean_graph_data';

    protected $fillable = [
        'year', 
        'term', 
        'section_id', 
        'subjects',
        'mean_scores', 
    ];

    protected $casts = [
        'subjects' => 'array',
        //'subjects' => 'array'
    ];
}
