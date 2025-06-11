<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'deduction_month',
        'amount',
        'deduction_type',
        'deduction_nature',
        'recurring_type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
