<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parents extends Model
{
    use HasFactory;
    protected $table = 'parents';
    protected $primaryKey = 'parent_id';

    public function students()
    {
         return $this->belongsToMany(Student::class, 'student_parents', 'parent_id', 'student_id');
    }

    public static function getParentStudents($parent_id)
    {
        return DB::table('student_parents')
                 ->join('students', 'students.student_id', '=', 'student_parents.student_id')
                 ->join('users', 'users.id', '=', 'students.student_user_id')
                 ->where('student_parents.parent_id', $parent_id)
                 ->select('users.name', 'students.*')
                 ->get(); 
    }
}