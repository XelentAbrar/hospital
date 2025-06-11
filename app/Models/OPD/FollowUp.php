<?php

namespace App\Models\OPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'follow_up_date',
        'notes',
    ];
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
