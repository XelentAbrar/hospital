<?php

namespace Database\Seeders;

use App\Models\Lab\LabTest;
use App\Models\Lab\TestCategory;
use App\Models\Lab\TestGroup;
use App\Models\Lab\Unit;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        TestGroup::truncate();
        Unit::truncate();
        TestCategory::truncate();
        LabTest::truncate();
        Schema::enableForeignKeyConstraints();
        
        $test_group = TestGroup::create([
            'name' => 'General',
            'status' => 'active',
        ]);
        $TestCategory = TestCategory::create([
            'test_group_id' => $test_group->id,
            'name' => 'General',
            'status' => 'active',
        ]);
        
        $level1 = DB::connection('mysql2')->table('labtests')->select('unit')->groupBy('unit')->get();
        foreach ($level1 as $dept) {
            Unit::create([
                'name' => $dept->unit,
                'status' => 'active',
            ]);
        }

        $level1 = DB::connection('mysql2')->table('labtests')->get();
        foreach ($level1 as $dept) {
            $level2 = DB::connection('mysql2')->table('mlabtest')->where('pid', $dept->Testid)->first();
            LabTest::create([
                'test_category_id' => $TestCategory->id,
                'name' => $dept->Name,
                'description' => null,
                'price' => $dept->Rate,
                'sample_required' => null,
                'reporting_time' => null,
                'min_value' => $level2?->minVal,
                'max_value' => $level2?->maxVal,
                'value_range' => null,
                'unit_id' => Unit::where('name', $dept->unit)->value('id') ?? null,
                'status' => 'active',
            ]);
        }

        
        
    }
}
