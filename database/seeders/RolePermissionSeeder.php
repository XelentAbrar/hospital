<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        PermissionRole::truncate();
        Schema::enableForeignKeyConstraints();

        $permissions = Permission::pluck('id');

        Role::find(1)->permissions()->attach($permissions);
        Role::find(7)->permissions()->attach($permissions);
        Role::find(8)->permissions()->attach($permissions);

        $permissions = Permission::where('route_name', 'Like', 'room-types.%')->orWhere('route_name', 'Like', 'units.%')->orWhere('route_name', 'Like', 'packages.%')->orWhere('route_name', 'Like', 'sub-packages.%')->orWhere('route_name', 'Like', 'test-groups.%')->orWhere('route_name', 'Like', 'test-categories.%')->orWhere('route_name', 'Like', 'lab-tests.%')->orWhere('route_name', 'Like', 'widal-tests.%')->orWhere('route_name', 'Like', 'patients.%')->orWhere('route_name', 'Like', 'expenses.%')->orWhere('route_name', 'Like', 'patient-tests.%')->orWhere('route_name', 'Like', 'daily-attendances.%')->pluck('id');
        Role::find(2)->permissions()->attach($permissions);
        Role::find(10)->permissions()->attach($permissions);


        $permissions = Permission::where('route_name', 'Like', 'services.%')->orWhere('route_name', 'Like', 'donors.%')->orWhere('route_name', 'Like', 'donations.%')->orWhere('route_name', 'Like', 'donor-funds.%')->orWhere('route_name', 'Like', 'patients.%')->orWhere('route_name', 'Like', 'appointments.%')->orWhere('route_name', 'Like', 'follow-ups.%')->orWhere('route_name', 'Like', 'patient-feedbacks.%')->orWhere('route_name', 'Like', 'categories.%')->orWhere('route_name', 'Like', 'expenses.%')->pluck('id');
        Role::find(3)->permissions()->attach($permissions);
        Role::find(10)->permissions()->attach($permissions);

        $permissions = Permission::where('route_name', 'Like', 'room-types.%')->orWhere('route_name', 'Like', 'rooms.%')->orWhere('route_name', 'Like', 'room-beds.%')->orWhere('route_name', 'Like', 'admissions.%')->orWhere('route_name', 'Like', 'patient-case-studies.%')->orWhere('route_name', 'Like', 'expenses.%')->pluck('id');
        Role::find(4)->permissions()->attach($permissions);
        Role::find(10)->permissions()->attach($permissions);

        $permissions = Permission::where('route_name', 'Like', 'operations.%')->pluck('id');
        Role::find(3)->permissions()->attach($permissions);
        Role::find(4)->permissions()->attach($permissions);
        Role::find(10)->permissions()->attach($permissions);

        $permissions = Permission::where('route_name', 'Like', 'employees.%')->orWhere('route_name', 'Like', 'daily-attendances.%')->orWhere('route_name', 'Like', 'holidays.%')->orWhere('route_name', 'Like', 'employee-allowances.%')
        ->orWhere('route_name', 'Like', 'employee-advances.%')
        ->orWhere('route_name', 'Like', 'employee-deductions.%')
        ->orWhere('route_name', 'Like', 'employee-warnings.%')
        ->orWhere('route_name', 'Like', 'employee-promotions.%')
        ->orWhere('route_name', 'Like', 'employee-terminations.%')
        ->orWhere('route_name', 'Like', 'departments.%')
        ->orWhere('route_name', 'Like', 'designations.%')
        ->orWhere('route_name', 'Like', 'allowances.%')
        ->orWhere('route_name', 'Like', 'shifts.%')
        ->orWhere('route_name', 'Like', 'attendance-machines.%')
        ->orWhere('route_name', 'Like', 'roster-types.%')
        ->orWhere('route_name', 'Like', 'grades.%')
        ->orWhere('route_name', 'Like', 'employee-shift-roster.%')
        ->orWhere('route_name', 'Like', 'leave-types.%')
        ->pluck('id');
        Role::find(5)->permissions()->attach($permissions);

        $permissions = Permission::where('route_name', 'Like', 'categories.%')->orWhere('route_name', 'Like', 'sub-categories.%')->orWhere('route_name', 'Like', 'uoms.%')->orWhere('route_name', 'Like', 'warehouses.%')
        ->orWhere('route_name', 'Like', 'warehouses.%')
        ->orWhere('route_name', 'Like', 'brands.%')
        ->orWhere('route_name', 'Like', 'products.%')
        ->orWhere('route_name', 'Like', 'salts.%')
        ->orWhere('route_name', 'Like', 'item_categories.%')
        ->orWhere('route_name', 'Like', 'item_types.%')
        ->orWhere('route_name', 'Like', 'suppliers.%')
        ->orWhere('route_name', 'Like', 'customers.%')
        ->orWhere('route_name', 'Like', 'taxes.%')
        ->orWhere('route_name', 'Like', 'departments.%')
        ->pluck('id');
        Role::find(6)->permissions()->attach($permissions);



        // $permissions = Permission::where('route_name', 'Like', 'uoms.%')->orWhere('route_name', 'Like', 'warehouses.%')->orWhere('route_name', 'Like', 'countries.%')->orWhere('route_name', 'Like', 'states.%')->orWhere('route_name', 'Like', 'cities.%')->orWhere('route_name', 'Like', 'control-accounts.%')->orWhere('route_name', 'Like', 'sub-control-accounts.%')->orWhere('route_name', 'Like', 'sub-head-accounts.%')->orWhere('route_name', 'Like', 'account-codes.%')->orWhere('route_name', 'Like', 'chart-of-accounts.%')->orWhere('route_name', 'Like', 'voucher-types.%')->orWhere('route_name', 'Like', 'vouchers.%')->orWhere('route_name', 'Like', 'vouchers-audit.%')->pluck('id');
        // Role::find(7)->permissions()->attach($permissions);
    }
}
