<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentParent extends Model
{
    use HasFactory;
    protected $table = 'student_parents';
    protected $primaryKey = 'id';

    public static function getStudentParents($student_id)
    {
        return DB::table('student_parents')
                 ->join('parents', 'parents.parent_id', '=', 'student_parents.parent_id')
                 ->where('student_parents.student_id', $student_id)
                 ->select('parents.*', 'student_parents.*')
                 ->get(); 
    }
}