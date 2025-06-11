<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        Role::create([
            'name' => 'Super Admin'
        ]);
        Role::create([
            'name' => 'Lab'
        ]);
        Role::create([
            'name' => 'OPD'
        ]);
        Role::create([
            'name' => 'IPD'
        ]);
        Role::create([
            'name' => 'HRM'
        ]);
        Role::create([
            'name' => 'Inventory'
        ]);
        Role::create([
            'name' => 'Accounts'
        ]);
        Role::create([
            'name' => 'Doctor'
        ]);
        Role::create([
            'name' => 'Patient'
        ]);
        Role::create([
            'name' => 'Receptionist'
        ]);
        Role::create([
            'name' => 'OT'
        ]);
    }
}
