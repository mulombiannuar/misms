<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsAnalysedMeanExams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_analysed_mean_exams', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('term');
            $table->integer('year');
            $table->integer('section_id');
            $table->integer('subjects_entry');
            $table->integer('section_entry');
            $table->integer('class_entry');
            $table->string('total_points');
            $table->string('average_points');
            $table->string('average_grade');
            $table->string('section_position');
            $table->string('class_position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_analysed_mean_exams');
    }
}
