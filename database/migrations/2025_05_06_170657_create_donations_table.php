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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('charges')->nullable();
            $table->string('donor')->nullable();
            $table->string('account_on')->nullable();
            $table->integer('donor_serial')->nullable();
            $table->string('donor_date')->nullable();
            $table->string('donor_time')->nullable();
            $table->string('narration')->nullable();
            $table->tinyInteger('is_printed')->nullable();
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
        Schema::dropIfExists('donations');
    }
};
