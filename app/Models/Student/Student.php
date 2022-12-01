<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'students';
    protected $primaryKey = 'student_id';

    /**
     * Get class section belonging to the student.
     *
     */
    public function section()
    {
        return $this->hasOne(Section::class, 'section_id', 'section_id');
    }
    /**
    * Get county belonging to the student.
    *
    */
    public function county()
    {
        return $this->hasOne('counties', 'county_id', 'county_id');
    }

    /**
    * Get sub county belonging to the student.
    *
    */
    public function subcounty()
    {
        return $this->hasOne('sub_counties', 'sub_id', 'sub_county');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public static function getStudents()
    {
        return  DB::table('users')
                  ->where(['users.deleted_at' => null, 'accessibility'=> 1, 'is_student' => 1])
                  ->join('students', 'students.student_user_id', '=', 'users.id' )
                  ->join('sections', 'sections.section_id', '=', 'students.section_id')
                  ->select(
                    'students.*', 
                    'users.id', 
                    'users.name', 
                    'users.email',
                    'sections.section_name', 
                    'sections.section_numeric', 
                    )
                  ->orderBy('admission_no', 'asc')
                  ->get();
    }

    public static function getStudentById($id)
    {
        return  DB::table('users')
                  ->where(['users.deleted_at' => null, 'accessibility'=> 1, 'id' => $id])
                  ->join('students', 'students.student_user_id', '=', 'users.id' )
                  ->join('sections', 'sections.section_id', '=', 'students.section_id')
                  ->select(
                    'students.*', 
                    'users.id', 
                    'users.name', 
                    'users.email',
                    'sections.section_name', 
                    'sections.section_numeric', 
                    )
                  ->first();
    }


}