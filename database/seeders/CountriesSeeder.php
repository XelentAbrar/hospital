<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\OPD\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Country::truncate();
        Schema::enableForeignKeyConstraints();

        $countries = [
            ['name' => 'United States', 'abrv' => 'US', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Canada', 'abrv' => 'CA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'United Kingdom', 'abrv' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Australia', 'abrv' => 'AU', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Germany', 'abrv' => 'DE', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'France', 'abrv' => 'FR', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'India', 'abrv' => 'IN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'China', 'abrv' => 'CN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Japan', 'abrv' => 'JP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brazil', 'abrv' => 'BR', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('countries')->insert($countries);
    }
}
