<?php

namespace Database\Seeders;

use App\Models\HRMS\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        LeaveType::truncate();
        Schema::enableForeignKeyConstraints();

        $leaveTypes = [
            ['name' => 'Sick Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Casual Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maternity Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paternity Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Annual Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unpaid Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Emergency Leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Compensatory Leave', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('leave_types')->insert($leaveTypes);
    }
}
