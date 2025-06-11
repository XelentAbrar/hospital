<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        $routes = [
            'allowances', 'daily-attendances', 'departments', 'designations', 'employee-advances',
            'employee-allowances', 'employee-deductions', 'employee-promotions', 'employees',
            'employee-terminations', 'employee-warnings', 'holidays', 'leave-types', 'shifts', 'brands', 'categories', 'customers', 'products', 'salts', 'item_types', 'item_categories','sub-categories', 'suppliers', 'taxes', 'uoms', 'warehouses', 'countries', 'states', 'cities', 'services', 'donors', 'donor-funds', 'patients', 'appointments', 'follow-ups', 'patient-feedbacks', 'units', 'test-groups','sub-packages' ,'packages', 'test-categories', 'lab-tests', 'widal-tests', 'patient-tests', 'room-types', 'rooms', 'room-beds', 'admissions', 'patient-case-studies', 'attendance-machines', 'roster-types', 'grades', 'control-accounts', 'sub-control-accounts', 'sub-head-accounts', 'account-codes', 'chart-of-accounts', 'voucher-types', 'vouchers','vouchers-audit','inventory-departments','operations','donations'
        ];

        foreach ($routes as $route) {
            $actions = ['index', 'create', 'show', 'edit', 'destroy'];

            foreach ($actions as $action) {
                if ($action === 'index') {
                    $displayName = ucfirst(str_replace('-', ' ', $route)) . ' Listing';
                } else if ($action === 'create') {
                    $displayName = 'Create ' . ucfirst(str_replace('-', ' ', $route));
                } else if ($action === 'edit') {
                    $displayName = 'Edit ' . ucfirst(str_replace('-', ' ', $route));
                } else if ($action === 'destroy') {
                    $displayName = 'Delete ' . ucfirst(str_replace('-', ' ', $route));
                }
                $routeName = $route . '.' . $action;

                Permission::create(['name' => $displayName, 'route_name' => $routeName]);
            }
        }

        Permission::create(['name' => 'Settings', 'route_name' => 'settings']);
    }
}
