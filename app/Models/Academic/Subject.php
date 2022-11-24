<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'subject_id';
    protected $table = 'subjects';

    /**
     * The students that belong to the subject.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects', 'subject_id', 'student_id');
    }

    /**
     * Teachers teaching the subject.
     */
    public function teachers()
    {
        return $this->belongsToMany(Users::class, 'subject_teachers', 'subject_id', 'user_id');
    }
}