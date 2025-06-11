<?php

namespace Database\Seeders;

use App\Models\HRMS\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Holiday::truncate();
        Schema::enableForeignKeyConstraints();

        $holidays = [
            ['name' => 'New Year\'s Day', 'start_date' => '2024-01-01', 'end_date' => '2024-01-01', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Independence Day', 'start_date' => '2024-07-04', 'end_date' => '2024-07-04', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Christmas Day', 'start_date' => '2024-12-25', 'end_date' => '2024-12-25', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thanksgiving', 'start_date' => '2024-11-28', 'end_date' => '2024-11-28', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Labor Day', 'start_date' => '2024-09-02', 'end_date' => '2024-09-02', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Memorial Day', 'start_date' => '2024-05-27', 'end_date' => '2024-05-27', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('holidays')->insert($holidays);
    }
}
