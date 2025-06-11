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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->foreignId('employee_id')->nullable()->after('password')->references('id')->on('employees')->onDelete('cascade');
        });
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::dropIfExists('users_details');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->foreignId('role_id')->nullable()->after('password')->references('id')->on('roles')->onDelete('set null');
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
        Schema::dropIfExists('user_role');
        Schema::create('users_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->nullable()->references('id')->on('users')->onDelete('set null');
            $table->string("nice_name")->nullable();
            $table->string("picture", 2048)->nullable();
            $table->date("dob")->nullable();
            $table->enum("martial_status", ["Single", "Married", "Divorced", "Widowed"])->nullable();
            $table->enum("gender", ["Male", "Female", "Children"])->nullable();
            $table->string("address_line1")->nullable();
            $table->string("address_line2")->nullable();
            $table->string("city_id")->nullable();
            $table->string("state_id")->nullable();
            $table->string("country_id")->nullable();
            $table->string("zip_code")->nullable();
            $table->string("phone")->nullable();
            $table->text("short_bio", 1000)->nullable();
            $table->text("profile", 1000)->nullable();
            $table->string("facebook")->nullable();
            $table->string("instagram")->nullable();
            $table->string("twitter")->nullable();
            $table->string("linkedin")->nullable();
            $table->string("youtube")->nullable();
            $table->timestamps();
        });
    }
};
