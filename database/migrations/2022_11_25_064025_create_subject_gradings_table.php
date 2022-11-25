<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectGradingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_gradings', function (Blueprint $table) {
            $table->id('grade_id');
            $table->string('grade_name');
            $table->string('min_score');
            $table->string('max_score');
            $table->string('score_remarks');
            $table->integer('form_numeric');
            $table->integer('subject_id');
            $table->integer('created_by');
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
        Schema::dropIfExists('subject_gradings');
    }
}