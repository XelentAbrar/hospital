<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'advance_amount',
        'start_deduction_from',
        'deduction_type',
        'deduction_amount',
        'deduction_months',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deductionSchedules()
    {
        return $this->hasMany(DeductionSchedule::class);
    }

}
