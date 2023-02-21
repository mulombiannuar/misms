<?php

namespace Database\Seeders;

use App\Models\Academic\Section;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $faker = Faker::create();
        for ($s=1000; $s <=2500 ; $s++) 
        { 
           $user = User::create([
                'name' => $faker->name(),
                'email' => $s.'@mulan.co.ke',
                'password' => Hash::make($s),
                'is_student' => 1,
            ]);
            $user->attachRole('student');

            Student::create([
                'upi' => $s,
                'status' => 1,
                'admission_no' => $s,
                'impaired' => 'No',
                'ward' => 'Kirimari',
                'student_image' => 'avatar.png',
                'student_user_id' => $user->id,
                'extra' => $faker->randomElement(['Football, Netball', 'Athletics, Longjumps', 'Tennis, Indoor games']),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'religion' => $faker->randomElement(['Christian', 'Islamist', 'Pagan', 'Others']),
                'section_id' => $faker->randomElement(Section::pluck('section_id')),
                'birth_date' => $faker->dateTimeBetween('-20 month', '+20 month'),
                'admission_date' => now(),
                'student_image' => 'avatar.png',
                'address' => 'P.O Box 2299, Embu',
                'primary_school' => $faker->name(). ' Primary School',
                'kcpe_year' => $faker->randomElement(['2018', '2019', '2020']),
                'kcpe_marks' => rand(250,420),
                'gender' => 'male',
                'county_id' => 21,
                'sub_county' => 131,
                'created_at' => now()
            ]);
        }
    }
}
