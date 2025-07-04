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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('patient_age')->nullable();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->string('age')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('patient_age');
        });
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('age');
        });
    }
};

