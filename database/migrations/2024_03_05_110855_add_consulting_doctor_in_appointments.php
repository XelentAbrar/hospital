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
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(["consulting_doctor"]);
            $table->dropColumn(["consulting_doctor"]);
            $table->foreignId("consulting_doctor_id")->nullable()->after('patient_id')->index()->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(["consulting_doctor_id"]);
            $table->dropColumn(["consulting_doctor_id"]);
            $table->foreignId("consulting_doctor")->nullable()->after('patient_id')->index()->references('id')->on('users')->onDelete('set null');
        });
    }
};
