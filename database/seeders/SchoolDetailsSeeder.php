<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('school_details')->insert([
            'id' => '1',
            'name' => 'Mulan High School',
            'code' => '650487975',
            'address' => 'P.O Box 12588, Kitale',
            'telephone' => '0704621587',
            'domain' => 'mulan.co.ke',
            'principal' => 'Anthony Wafula',
            'email' => 'info@mulan.co.ke',
            'motto' => 'Servicing Technology',
            'logo' => 'mulan-logo.png',
            'category' => '1',
        ]);
    }
}