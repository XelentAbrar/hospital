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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("patient_id")->nullable()->index()->references('id')->on('patients')->onDelete('set null');
            $table->enum("type", ["General", "Specialist"]);
            $table->foreignId("consulting_doctor")->nullable()->index()->references('id')->on('users')->onDelete('set null');
            $table->date("appointment_date")->nullable();
            $table->time("appointment_time")->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('appointments');
        Schema::enableForeignKeyConstraints();
    }
};
