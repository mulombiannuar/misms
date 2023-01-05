<?php

namespace App\Models\Academic;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    use HasFactory;
    protected $table = 'subject_teachers';
    protected $primaryKey = 'sub_id';

    /**
     * Get teacher belonging to the subject.
     *
     */
    public function teacher()
    {
         return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * Get section belonging to the teacher subject.
     *
     */
    public function section()
    {
         return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

     /**
     * Get subjects belonging to the teacher.
     *
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teachers', 'subject_id', 'subject_id');
    }

    ////Get teacher subject teachers
    public function getSubjectsTeachers()
    {
          return $this->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.subject_id')
                      ->join('sections', 'sections.section_id', '=', 'subject_teachers.section_id')
                      ->join('users', 'users.id', '=', 'subject_teachers.user_id')
                      ->select('users.name','sections.*','subjects.subject_name', 'subjects.subject_code', 'subject_teachers.created_at', 'subject_teachers.sub_id')
                      ->orderBy('subjects.subject_name', 'asc')
                      ->get();
    }

    ////Get teacher subjects
    public function getTeacherSubjects($user_id)
    {
         return $this->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.subject_id')
                     ->join('sections', 'sections.section_id', '=', 'subject_teachers.section_id')
                     ->select('sections.*','subjects.subject_name', 'subjects.subject_code', 'subject_teachers.created_at', 'subject_teachers.sub_id')
                     ->where('user_id', $user_id)
                     ->orderBy('subjects.subject_name', 'asc')
                     ->get();
    }

    public function getTeacherSubjectsBySectionId($user_id, $section_id)
    {
         return $this->join('subjects', 'subject_teachers.subject_id', '=', 'subjects.subject_id')
                     ->where(['user_id' => $user_id, 'section_id' => $section_id])
                     ->select('subjects.subject_name', 'subjects.subject_id', 'subject_teachers.created_at')
                     ->orderBy('subjects.subject_name', 'asc')
                     ->get();
    }

    ///get subject teacher by section id
    public function getSubjectTeacherBySectionId($subject_id, $section_id)
    {
     return $this->where([
                    'subject_id' => $subject_id, 
                    'section_id' => $section_id
                    ])
                    ->join('users', 'users.id', '=', 'subject_teachers.user_id')
                    ->join('profiles', 'profiles.user_id', '=', 'users.id')
                    ->select('users.name', 'profiles.name_initial')
                    ->first();
    }

    //Get teacher classes by subject_id
    public function getTeacherClassesBySubjectId($user_id, $subject_id)
    {
        return $this->join('sections', 'subject_teachers.section_id', '=', 'sections.section_id')
                    ->where([
                         'user_id' => $user_id, 
                         'subject_id' => $subject_id
                         ])
                    ->select('sections.section_name', 'sections.section_numeric')
                    ->orderBy('sections.section_numeric', 'asc')
                    ->get();;
    }

  
     
}