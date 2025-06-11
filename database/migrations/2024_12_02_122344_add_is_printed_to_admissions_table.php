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
        Schema::table('admissions', function (Blueprint $table) {
            $table->boolean('is_printed')->nullable();
            $table->boolean('is_discharged')->nullable();
        });
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('is_printed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {

            $table->dropColumn('is_printed');
            $table->dropColumn('is_discharged');

        });Schema::table('appointments', function (Blueprint $table) {

            $table->dropColumn('is_printed');

        });
    }
};
