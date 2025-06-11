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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->decimal('advance_amount', 10, 2);
            $table->date('start_deduction_from');
            $table->string('deduction_type'); // fixed_amount or by_month
            $table->decimal('deduction_amount', 10, 2)->nullable(); // for fixed_amount type
            $table->integer('deduction_months')->nullable(); // for by_month type
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
