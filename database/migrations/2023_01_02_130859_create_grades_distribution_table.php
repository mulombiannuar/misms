<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesDistributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades_distribution', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('section_id')->nullable();
            $table->json('grades');
            $table->string('total_students');
            $table->string('total_points');
            $table->string('average_points');
            $table->string('average_grade');
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
        Schema::dropIfExists('grades_distribution');
    }
}
