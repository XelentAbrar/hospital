<?php

namespace App\Models\IPD;

use App\Models\HRMS\Employee;
use App\Models\OPD\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'date',
        'service_id',
        'doctor_id',
        'amount',
        'is_doctor',
        'no_of_days',
        'day_charges',
        'service_amount',
        'is_posted',
        'desc',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'doctor_id');
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }
    // public function getIsDoctor($value){
    //     return value == 1 ? true : false;
    // }
}
