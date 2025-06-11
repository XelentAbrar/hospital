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
        Schema::create('appointment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("appointment_id")->nullable()->index()->references('id')->on('appointments')->onDelete('set null');
            $table->foreignId("service_id")->nullable()->index()->references('id')->on('services')->onDelete('set null');
            $table->decimal("fee")->nullable();
            $table->enum("fee_status", ["Pending", "Complete"])->nullable();
            $table->timestamps();
        });

        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId("appointment_id")->nullable()->index()->references('id')->on('appointments')->onDelete('set null');
            $table->date("follow_up_date")->nullable();
            $table->text("notes", 1000)->nullable();
            $table->timestamps();
        });

        Schema::create('patient_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId("appointment_id")->nullable()->index()->references('id')->on('appointments')->onDelete('set null');
            $table->text("feedback", 1000)->nullable();
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
        Schema::dropIfExists('patient_feedback');
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('appointment_details');
        Schema::enableForeignKeyConstraints();
    }
};
