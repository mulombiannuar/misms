<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id('score_id');
            $table->integer('exam_record_id');
            $table->integer('class_numeric');
            $table->integer('student_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('teacher_id');
            $table->integer('exam_id');
            $table->integer('score');
            $table->integer('year');
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
        Schema::dropIfExists('scores');
    }
}
