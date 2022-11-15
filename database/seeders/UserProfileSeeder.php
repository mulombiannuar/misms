<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($s=0; $s <=8000 ; $s++) 
        { 
           $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('Password'),
                'is_student' => 0,
            ]);
            $user->attachRole('teacher');

            Profile::create([
                'user_id' => $user->id,
                'mobile_no' => $faker->phoneNumber(),
                'birth_date' => $faker->dateTimeBetween('-20 month', '+20 month'),
                'user_image' => 'avatar.png',
                'address' => 'P.O Box 2299, Embu',
                'national_id' => rand(20554548,36987863),
                'religion' => 'Christian',
                'gender' => 'male',
                'county' => 21,
                'sub_county' => 131,
            ]);
        }
    }
}