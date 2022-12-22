<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmittedScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submitted_scores', function (Blueprint $table) {
            $table->id('subm_id');
            $table->integer('session');
            $table->integer('numbers');
            $table->integer('exam_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('teacher_id');
            $table->integer('class_numeric');
            $table->integer('submitted')->default(0);
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
        Schema::dropIfExists('submitted_scores');
    }
}
