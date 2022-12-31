<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id('exam_id');
            $table->string('name');
            $table->string('notes');
            $table->string('class_numeric');
            $table->string('converted');
            $table->string('conversion');
            $table->integer('created_by');
            $table->integer('term');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('deadline_date');
            $table->integer('is_closed')->default(0);
            $table->integer('year')->default(Date('Y'));
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('exams');
    }
}
