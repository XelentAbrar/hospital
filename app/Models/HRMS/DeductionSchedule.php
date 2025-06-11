<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_id',
        'month_year',
        'amount',
        'status',
        'stop_reason',
    ];

    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }
}
