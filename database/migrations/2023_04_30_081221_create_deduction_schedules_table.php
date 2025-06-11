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
        Schema::create('deduction_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advance_id');
            $table->foreign('advance_id')->references('id')->on('advances')->onDelete('cascade');
            $table->string('month_year');
            $table->decimal('amount', 8, 2);
            $table->string('status');
            $table->text('stop_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deduction_schedules');
    }
};
