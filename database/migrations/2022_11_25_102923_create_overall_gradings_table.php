<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverallGradingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overall_gradings', function (Blueprint $table) {
            $table->id('grade_id');
            $table->string('grade_name');
            $table->string('min_score');
            $table->string('max_score');
            $table->integer('form_numeric');
            $table->integer('created_by');
            $table->string('teacher_remarks');
            $table->string('principal_remarks');
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
        Schema::dropIfExists('overall_gradings');
    }
}