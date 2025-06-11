<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTermination extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'termination_type',
        'termination_reason',
        'termination_date',
        'approved_by',
    ];

    // protected $with = ['issuedBy'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

}
