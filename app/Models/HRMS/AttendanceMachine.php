<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceMachine extends Model
{
    use HasFactory;

    protected $fillable = [
        'location', 'ip_address'
    ];
}
