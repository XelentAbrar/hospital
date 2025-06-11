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
            $table->string('slip_no')->nullable();
            $table->string('discharge_slip_no')->nullable();
        });

        Schema::table('admission_details', function (Blueprint $table) {
            $table->string('service_amount')->nullable();
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
            $table->dropColumn('slip_no');
            $table->dropColumn('discharge_slip_no');
        });

        Schema::table('admission_details', function (Blueprint $table) {
            $table->dropColumn('service_amount');
        });
    }
};

