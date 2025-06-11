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
            $table->string("patient_name")->nullable()->after('appointment_time');
            $table->string("patient_cnic")->nullable()->after('patient_name');
            $table->date("patient_dob")->nullable()->after('patient_cnic');
            $table->enum("patient_gender", ["Male", "Female", "Prefer_not_to_say","Children"])->nullable()->after('patient_dob');
            $table->string("patient_relation_name")->nullable()->after('patient_gender');
            $table->enum("patient_relation_type", ['Guardian', 'Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Relative'])->nullable()->after('patient_relation_name');
            $table->string("patient_address")->nullable()->after('patient_relation_type');
            $table->string("patient_city_id")->nullable()->after('patient_address');
            $table->string("patient_state_id")->nullable()->after('patient_city_id');
            $table->string("patient_country_id")->nullable()->after('patient_state_id');
            $table->string("patient_phone")->nullable()->after('patient_country_id');
            $table->string("patient_email")->nullable()->after('patient_phone');
            $table->string("patient_blood_group")->nullable()->after('patient_email');
            $table->string("patient_rh_factor")->nullable()->after('patient_blood_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn("patient_name");
            $table->dropColumn("patient_cnic");
            $table->dropColumn("patient_dob");
            $table->dropColumn("patient_gender");
            $table->dropColumn("patient_relation_name");
            $table->dropColumn("patient_relation_type");
            $table->dropColumn("patient_address");
            $table->dropColumn("patient_city_id");
            $table->dropColumn("patient_state_id");
            $table->dropColumn("patient_country_id");
            $table->dropColumn("patient_phone");
            $table->dropColumn("patient_email");
            $table->dropColumn("patient_blood_group");
            $table->dropColumn("patient_rh_factor");
        });
    }
};
