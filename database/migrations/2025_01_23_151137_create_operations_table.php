<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('operation_date')->nullable();
            $table->string('file_no')->nullable();
            $table->string('ot_number')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('patient_age')->nullable();
            $table->string('patient_phone')->nullable();
            $table->string('patient_cnic')->nullable();
            $table->string('patient_gender')->nullable();
            $table->string('patient_address')->nullable();
            $table->string('patient_relation_name')->nullable();
            $table->string('surgeon_name')->nullable();
            $table->string('anasthetic_doc_name')->nullable();
            $table->boolean('is_hepatitus')->nullable();
            $table->string('anasthesia_type')->nullable();
            $table->string('procedure_type')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status',['Pending','Complete'])->nullable();
            $table->string('advance_amount')->nullable();
            $table->string('received_amount')->nullable();
            $table->string('welfare')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('assistant_male')->nullable();
            $table->string('assistant_female')->nullable();
            $table->string('lap_chole')->nullable();
            $table->string('is_printed')->nullable();

            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('room_bed_id')->nullable();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('bed_id')->references('id')->on('room_beds')->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('room_types')->onDelete('set null');
            $table->foreign('room_bed_id')->references('id')->on('room_beds')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('set null');


            $table->timestamps();
        });
        Schema::create('operation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("operation_id")->nullable()->index()->references('id')->on('operations')->onDelete('set null');
            $table->foreignId("service_id")->nullable()->index()->references('id')->on('services')->onDelete('set null');
            $table->foreignId("doctor_id")->nullable()->index()->references('id')->on('employees')->onDelete('set null');

            $table->string('date')->nullable();
            $table->string('is_doctor')->nullable();
            $table->string('service_amount')->nullable();
            $table->string('doctor_amount')->nullable();
            $table->string('is_posted')->nullable();
            $table->string('no_of_days')->nullable();
            $table->text('description', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_details');
        Schema::dropIfExists('operations');
    }
};
