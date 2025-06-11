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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('probation_start_date')->nullable()->change();
            $table->date('notice_period_end_date')->nullable()->change();
            $table->date('notice_period_start_date')->nullable()->change();
            $table->date('probation_end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('notice_period_start_date')->change();
            $table->date('notice_period_end_date')->change();
            $table->date('probation_end_date')->change();
            $table->date('probation_start_date')->change();
        });
    }
};
