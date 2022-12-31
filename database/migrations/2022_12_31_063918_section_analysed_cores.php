<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SectionAnalysedCores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_analysed_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('score_id');
            $table->integer('exam_id');
            $table->string('subject_grade');
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
        Schema::dropIfExists('section_analysed_cores');
    }
}
