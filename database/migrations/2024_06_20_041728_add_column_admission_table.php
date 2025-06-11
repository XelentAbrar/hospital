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
        Schema::table('admissions', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('relation_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('name')->nullable();
            $table->string('cnic')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('advance_amount')->nullable();
            $table->string('received_amount')->nullable();
            $table->enum('status',['Pending','Complete'])->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->unsignedBigInteger('type')->nullable();
            $table->string('refered_by')->nullable();
            $table->string('welfare')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('bed_id')->references('id')->on('room_beds')->onDelete('set null');
            $table->foreign('type')->references('id')->on('room_types')->onDelete('set null');
        });

        Schema::create('admission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("admission_id")->nullable()->index()->references('id')->on('admissions')->onDelete('set null');
            $table->foreignId("service_id")->nullable()->index()->references('id')->on('services')->onDelete('set null');
            $table->foreignId("doctor_id")->nullable()->index()->references('id')->on('employees')->onDelete('set null');
            $table->date('date')->nullable();
            $table->string('amount')->nullable();
            $table->text('desc', 500)->nullable();
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

        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('relation_name');
            $table->dropColumn('dob');
            $table->dropColumn('phone');
            $table->dropColumn('gender');
            $table->dropColumn('status');
            $table->dropColumn('department_id');
            $table->dropColumn('type');
            $table->dropColumn('refered_by');
        });

        Schema::dropIfExists('admission_details');
    }
};
