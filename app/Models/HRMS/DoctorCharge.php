<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorCharge extends Model
{
    use HasFactory;


    protected $fillable = [
        'employee_id',
        'visiting_charges',
        'consulting_charges',
    ];


    function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
