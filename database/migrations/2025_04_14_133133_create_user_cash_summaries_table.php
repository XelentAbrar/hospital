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
        Schema::create('user_cash_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('receipt_count')->default(0);
            $table->string('total_amount')->nullable();
            $table->string('collected_amount')->nullable();
            $table->string('remaining_amount')->nullable();
            $table->string('opening_balance')->nullable();
            $table->enum('status', ['pending', 'complete'])->default('pending');
            $table->string('activity_time')->nullable();
            $table->boolean('approved_by')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cash_summaries');
    }
};
