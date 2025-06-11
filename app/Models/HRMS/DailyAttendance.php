<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'checkin_type',
        'checkout_type',
        'remarks',
        'late'
    ];

    public function employee()
{
    return $this->belongsTo(Employee::class);
}
}
