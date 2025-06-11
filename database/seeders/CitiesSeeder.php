<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        City::truncate();
        Schema::enableForeignKeyConstraints();

        $cities = [
            ['name' => 'Los Angeles', 'abrv' => 'LA', 'country_id' => 1, 'state_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Houston', 'abrv' => 'HOU', 'country_id' => 1, 'state_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Toronto', 'abrv' => 'TOR', 'country_id' => 2, 'state_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Montreal', 'abrv' => 'MTL', 'country_id' => 2, 'state_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Munich', 'abrv' => 'MUC', 'country_id' => 5, 'state_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Berlin', 'abrv' => 'BER', 'country_id' => 5, 'state_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sydney', 'abrv' => 'SYD', 'country_id' => 4, 'state_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Melbourne', 'abrv' => 'MEL', 'country_id' => 4, 'state_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mumbai', 'abrv' => 'BOM', 'country_id' => 7, 'state_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chennai', 'abrv' => 'MAA', 'country_id' => 7, 'state_id' => 10, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('cities')->insert($cities);
    }
}
