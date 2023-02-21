<?php

namespace Database\Seeders;

use App\Models\Academic\SubmittedScore;
use App\Models\Student\Student;
use App\Models\Student\StudentSubject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateStudentsScoresSeeder extends Seeder
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
        $studentSubjects = new StudentSubject();
        $students = Student::get();
        $scores = SubmittedScore::where('class_numeric', $class_numeric)->get();
        DB::table('scores')->truncate();

        for ($i=0; $i <count($students) ; $i++) 
        { 
            $subjects = $studentSubjects->getStudentSubjects($students[$i]->student_user_id);
            for ($j=0; $j <count($scores) ; $j++) 
            { 
                for ($k=0; $k <count($subjects) ; $k++) 
                { 
                     if ($students[$i]->section_id == $scores[$j]->section_id 
                     && $scores[$j]->subject_id == $subjects[$k]->subject_id) 
                     {
                        DB::table('scores')->insert([
                            'year' => Date('Y'),
                            'exam_id' => $exam_id, 
                            'score' => rand(30, 90), 
                            'section_id' => $students[$i]->section_id, 
                            'subject_id' => $subjects[$k]->subject_id,
                            'teacher_id' => $scores[$j]->teacher_id,
                            'student_id' => $students[$i]->student_id, 
                            'class_numeric' => $class_numeric,
                            'exam_record_id' => $scores[$j]->subm_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                     }
                }
            }
        }
    }
}
