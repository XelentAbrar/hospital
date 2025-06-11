<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeShiftRoster extends Model
{
    use HasFactory;
    protected $table = 'employee_shift_roster';

    protected $fillable = [
        'roster_date',
        'employee_id',
        'department_id',
        'duty_department_id',
        'shift_id',
        'rest_day',
        'holiday_status',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function dutyDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'duty_department_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
