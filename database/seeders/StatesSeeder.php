<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        State::truncate();
        Schema::enableForeignKeyConstraints();

        $states = [
            ['name' => 'California', 'abrv' => 'CA', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Texas', 'abrv' => 'TX', 'country_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ontario', 'abrv' => 'ON', 'country_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quebec', 'abrv' => 'QC', 'country_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bavaria', 'abrv' => 'BY', 'country_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Berlin', 'abrv' => 'BE', 'country_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'New South Wales', 'abrv' => 'NSW', 'country_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Victoria', 'abrv' => 'VIC', 'country_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maharashtra', 'abrv' => 'MH', 'country_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tamil Nadu', 'abrv' => 'TN', 'country_id' => 7, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('states')->insert($states);
    }
}
