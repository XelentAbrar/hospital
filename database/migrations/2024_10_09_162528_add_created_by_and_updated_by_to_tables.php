<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tables = [
            'admissions',
            'appointments',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
            });
        }
    }

    public function down()
    {
        $tables = [
            'admissions',
            'appointments',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
};
