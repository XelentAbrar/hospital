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
        Schema::create('users_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->nullable()->references('id')->on('users')->onDelete('set null');
            $table->string("nice_name")->nullable();
            $table->string("picture", 2048)->nullable();
            $table->date("dob")->nullable();
            $table->enum("martial_status", ["Single", "Married", "Divorced", "Widowed"])->nullable();
            $table->enum("gender", ["Male", "Female", "Children"])->nullable();
            $table->string("address_line1")->nullable();
            $table->string("address_line2")->nullable();
            $table->string("city_id")->nullable();
            $table->string("state_id")->nullable();
            $table->string("country_id")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("phone")->nullable();
            $table->text("short_bio", 1000)->nullable();
            $table->text("profile", 1000)->nullable();
            $table->string("facebook")->nullable();
            $table->string("instagram")->nullable();
            $table->string("twitter")->nullable();
            $table->string("linkedin")->nullable();
            $table->string("youtube")->nullable();
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
        Schema::dropIfExists('users_details');
    }
};
