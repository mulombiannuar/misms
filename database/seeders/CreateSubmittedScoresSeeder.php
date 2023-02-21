<?php

namespace Database\Seeders;

use App\Models\Academic\Section;
use App\Models\Academic\Subject;
use App\Models\Admin\Session;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateSubmittedScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exam_id = 1;
        $class_numeric = 4;
        $subjects = Subject::get();
        $session = Session::getActiveSession()->session;
        $sections = Section::where('section_numeric', $class_numeric)->get();
        $teachers = User::where(['accessibility' => 1, 'is_student' => 0])->pluck('id');

        for ($s=0; $s <count($sections) ; $s++) 
        { 
            for ($sub=0; $sub <count($subjects) ; $sub++) 
            { 
                DB::table('submitted_scores')->insert([
                    'numbers' => 0,
                    'exam_id' => $exam_id,
                    'session' => $session,
                    'class_numeric' =>$class_numeric,
                    'section_id' => $sections[$s]->section_id,
                    'subject_id' => $subjects[$sub]->subject_id,
                    'teacher_id' => $teachers[rand(0, 5)],
                    'created_at' => now()
                ]);
            }
        }
    }
}
