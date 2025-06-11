<?php

namespace Database\Seeders;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Country::factory()->count(20)->create();
        State::factory()->count(50)->create();
        City::factory()->count(100)->create();
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            DesignationSeeder::class,
            GradeSeeder::class,
            ShiftSeeder::class,
            HolidaySeeder::class,
            LeaveTypeSeeder::class,
            ServiceSeeder::class,
            CountriesSeeder::class,
            StatesSeeder::class,
            CitiesSeeder::class,
            // ControlAccountsTableSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Xelent Solutions',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
        ]);
        \App\Models\UserRole::create([
            'user_id' => $user->id,
            'role_id' => 1,
        ]);
    }
}
