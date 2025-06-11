<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $table = 'experience';
    protected $fillable = ['company', 'designation', 'job_period', 'responsibility', 'skills'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
