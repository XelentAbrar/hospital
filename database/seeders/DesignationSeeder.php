<?php

namespace Database\Seeders;

use App\Models\HRMS\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Designation::truncate();
        Schema::enableForeignKeyConstraints();

        $designations = [
            ['name' => 'Cardiologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Neurologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Orthopedic Surgeon', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pediatrician', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gynecologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oncologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Radiologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dermatologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Emergency Physician', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Urologist', 'is_doctor' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nurse', 'is_doctor' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lab Technician', 'is_doctor' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pharmacist', 'is_doctor' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Receptionist', 'is_doctor' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Janitor', 'is_doctor' => false, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('designations')->insert($designations);
    }
}
