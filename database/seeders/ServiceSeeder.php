<?php

namespace Database\Seeders;

use App\Models\OPD\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Service::truncate();
        Schema::enableForeignKeyConstraints();

        $services = [
            ['name' => 'General Consultation', 'type' => 'OPD', 'charges' => 500, 'description' => 'General health checkup and consultation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cardiology Consultation', 'type' => 'OPD', 'charges' => 1500, 'description' => 'Specialized heart checkup and consultation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Orthopedic Surgery', 'type' => 'IPD', 'charges' => 20000, 'description' => 'Surgical procedures related to bones and joints', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pediatric Care', 'type' => 'OPD', 'charges' => 800, 'description' => 'Health care services for children', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maternity Services', 'type' => 'IPD', 'charges' => 25000, 'description' => 'Comprehensive maternity care', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Radiology Services', 'type' => 'Other', 'charges' => 3000, 'description' => 'Diagnostic imaging services', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dermatology Consultation', 'type' => 'OPD', 'charges' => 1200, 'description' => 'Skin-related health checkup and consultation', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Emergency Services', 'type' => 'Other', 'charges' => 5000, 'description' => 'Immediate and urgent medical care', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Physiotherapy', 'type' => 'OPD', 'charges' => 700, 'description' => 'Therapy for rehabilitation and physical recovery', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemotherapy', 'type' => 'IPD', 'charges' => 15000, 'description' => 'Cancer treatment with chemotherapy', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('services')->insert($services);
    }
}
