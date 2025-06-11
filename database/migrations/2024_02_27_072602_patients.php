<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("cnic")->nullable();
            $table->date("dob")->nullable();
            $table->enum("gender", ["Male", "Female", "Children"])->nullable();
            $table->string("relation_name")->nullable();
            $table->enum("relation_type", ['Guardian', 'Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Relative'])->nullable();
            $table->string("address")->nullable();
            $table->string("city_id")->nullable();
            $table->string("state_id")->nullable();
            $table->string("country_id")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("blood_group")->nullable();
            $table->string("rh_factor")->nullable();
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
        Schema::dropIfExists('patients');
    }
};
