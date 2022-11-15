<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignIdFor(User::class);
            $table->string('gender');
            $table->string('birth_date');
            $table->integer('county');
            $table->integer('sub_county');
            $table->string('mobile_no');
            $table->string('religion');
            $table->string('national_id');
            $table->string('address');
            $table->string('user_image')->default('avatar.png');
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
        Schema::dropIfExists('profiles');
    }
}