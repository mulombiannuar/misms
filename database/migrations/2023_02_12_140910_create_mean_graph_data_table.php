<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeanGraphDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mean_graph_data', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->string('year');
            $table->integer('section_id')->nullable();
            $table->json('mean_scores');
            $table->string('subjects');
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
        Schema::dropIfExists('mean_graph_data');
    }
}
