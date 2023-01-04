<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects_analysis', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->json('grades');
            $table->integer('subject_id');
            $table->integer('section_id')->nullable();
            $table->string('total_students');
            $table->string('total_points');
            $table->string('average_points');
            $table->string('total_marks');
            $table->string('average_marks');
            $table->string('average_grade');
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
        Schema::dropIfExists('subjects_analysis');
    }
}
