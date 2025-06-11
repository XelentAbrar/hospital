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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('password', 60)->nullable();
            $table->string('mobile')->nullable();
            $table->string('landline')->nullable();
            $table->string('street_address')->nullable();
            $table->integer('postal_code')->nullable();
            $table->date('doj')->nullable();
            $table->date('dob')->nullable();
            $table->date('probation_start_date');
            $table->date('probation_end_date');
            $table->date('notice_period_start_date');
            $table->date('notice_period_end_date');
            $table->enum('gender', ['Male', 'Female', 'Others'])->nullable();
            $table->unsignedBigInteger('reporting_to')->nullable();
            $table->string("image")->nullable();
            $table->boolean('login_allowed')->default(false);
            $table->string('slack_id', 60)->nullable();
            $table->text('about_employee')->nullable();
            $table->string('religion', 60)->nullable(); 
            $table->string('emergency_contact', 60)->nullable();
            $table->text('skills')->nullable();
            $table->enum('employee_type', ['Full Time', 'Part Time', 'On Contract', 'Internship', 'Trainee'])->nullable();
            $table->enum('marital_status', ['Unmarried', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_location')->nullable();
            $table->string('tax_payer_id')->nullable();
            $table->string('basic_salary')->nullable();
            $table->enum('salary_currency', ['PKR', 'USD'])->nullable();
            $table->timestamps();

            $table->foreign('reporting_to')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
