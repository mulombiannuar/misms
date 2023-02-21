<?php

namespace Database\Seeders;

use App\Models\Academic\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateStudentsSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = DB::table('students')->where('student_id', '>', 950)->get();
        $csubjects =  DB::table('subjects')->where('optionality', 'Compulsory')->get();

        for ($s=0; $s <count($students) ; $s++) 
        { 
            //store compulsory subjects
            // for ($sub=0; $sub <count($csubjects) ; $sub++) 
            // {
            //     DB::table('student_subjects')->insert([
            //         'status' => 1,
            //         'subject_id' => $csubjects[$sub]->subject_id, 
            //         'student_id' => $students[$s]->student_user_id,
            //     ]); 
            // }

            //store technical subjects
            $subjects = [6, 11];
            DB::table('student_subjects')->insert([
                'status' => 1,
                //'subject_id' => $subjects[rand(0, 1)], 
                'subject_id' => 4, 
                'student_id' => $students[$s]->student_user_id,
            ]); 
        }
    }
}
