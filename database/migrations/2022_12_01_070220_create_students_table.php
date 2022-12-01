<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('student_user_id');
            $table->string('admission_no');
            $table->date('admission_date');
            $table->integer('section_id');
            $table->string('upi');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('address');
            $table->string('religion');
            $table->string('impaired');
            $table->integer('county_id');
            $table->integer('sub_county');
            $table->string('ward');
            $table->string('extra')->nullable();
            $table->string('kcpe_year');
            $table->string('primary_school');
            $table->string('kcpe_marks');
            $table->integer('status');
            $table->string('student_image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}