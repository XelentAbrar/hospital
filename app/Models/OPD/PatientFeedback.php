<?php

namespace App\Models\OPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientFeedback extends Model
{
    use HasFactory;

    protected $table = 'patient_feedback';


    protected $fillable = [
        'appointment_id',
        'feedback',
    ];
}
