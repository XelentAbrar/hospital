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
        Schema::create('patient_case_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("patient_id")->nullable()->index()->references('id')->on('patients')->onDelete('set null');
            $table->text('food_allergies', 500)->nullable();
            $table->text('tendency_bleed', 500)->nullable();
            $table->text('heart_disease', 500)->nullable();
            $table->text('high_blood_pressure', 500)->nullable();
            $table->text('diabetic', 500)->nullable();
            $table->text('surgery', 500)->nullable();
            $table->text('accident', 500)->nullable();
            $table->text('family_medical_history', 500)->nullable();
            $table->text('current_medication', 500)->nullable();
            $table->text('female_pregnancy', 500)->nullable();
            $table->text('breast_feeding', 500)->nullable();
            $table->text('health_insurance', 500)->nullable();
            $table->text('notes', 500)->nullable();
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
        Schema::dropIfExists('patient_case_studies');
    }
};
