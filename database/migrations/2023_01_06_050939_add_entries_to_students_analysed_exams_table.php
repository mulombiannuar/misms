<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntriesToStudentsAnalysedExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students_analysed_exams', function (Blueprint $table) {
            $table->string('section_entry');
            $table->string('class_entry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students_analysed_exams', function (Blueprint $table) {
            $table->dropColumn('section_entry');
            $table->dropColumn('class_entry');
        });
    }
}
