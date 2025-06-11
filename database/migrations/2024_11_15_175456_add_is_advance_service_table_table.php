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
        Schema::table('services', function (Blueprint $table) {
            $table->tinyInteger("is_advance")->default(0);
        });

        Schema::table('admission_details', function (Blueprint $table) {
            $table->tinyInteger("is_posted")->default(0);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('is_advance');
        });

        Schema::table('admission_details', function (Blueprint $table) {
            $table->dropColumn('is_posted');
        });
    }
};
