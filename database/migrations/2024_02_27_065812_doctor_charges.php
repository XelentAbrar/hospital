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
        Schema::create('doctor_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->nullable()->references('id')->on('users')->onDelete('set null');
            $table->integer("visiting_charges")->nullable();
            $table->integer("consulting_charges")->nullable();
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
        Schema::dropIfExists('doctor_charges');
    }
};
