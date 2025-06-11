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
        Schema::table('employee_terminations', function (Blueprint $table) {
            $table->dropforeign(['approved_by']);
            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_terminations', function (Blueprint $table) {
            $table->foreignId('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
