<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeanScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mean_scores', function (Blueprint $table) {
           $table->id('score_id');
           $table->integer('class_numeric');
           $table->integer('student_id');
           $table->integer('section_id');
           $table->integer('subject_id');
           $table->integer('score');
           $table->integer('term');
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
        Schema::dropIfExists('mean_scores');
    }
}
