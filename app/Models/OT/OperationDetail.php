<?php

namespace App\Models\OT;

use App\Models\HRMS\Employee;
use App\Models\OPD\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_id',
        'service_id',
        'doctor_id',
        'date',
        'is_doctor',
        'service_amount',
        'doctor_amount',
        'is_posted',
        'description',
        'no_of_days',
     ];

     public function service(): BelongsTo
     {
         return $this->belongsTo(Service::class);
     }
 
     public function doctor(): BelongsTo
     {
         return $this->belongsTo(Employee::class, 'doctor_id');
     }
 
     public function operation(): BelongsTo
     {
         return $this->belongsTo(Operation::class);
     }
}
