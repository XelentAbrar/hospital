<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;
    protected $fillable = [ 'name', 'notes', ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_allowance')
                    ->withPivot('amount', 'effective_date')
                    ->withTimestamps();
    }
}
