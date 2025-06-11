<?php

namespace Database\Seeders;

use App\Models\HRMS\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Shift::truncate();
        Schema::enableForeignKeyConstraints();

        $shifts = [
            ['name' => 'Morning Shift', 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'late_count_time' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Afternoon Shift', 'start_time' => '14:00:00', 'end_time' => '22:00:00', 'late_count_time' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Night Shift', 'start_time' => '22:00:00', 'end_time' => '06:00:00', 'late_count_time' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Day Shift', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'late_count_time' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Evening Shift', 'start_time' => '16:00:00', 'end_time' => '00:00:00', 'late_count_time' => 15, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('shifts')->insert($shifts);
    }
}
