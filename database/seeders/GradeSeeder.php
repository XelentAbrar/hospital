<?php

namespace Database\Seeders;

use App\Models\HRMS\Grade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Grade::truncate();
        Schema::enableForeignKeyConstraints();

        $grades = [
            ['name' => 'Grade A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade B', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade C', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade D', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grade E', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('grades')->insert($grades);
    }
}
