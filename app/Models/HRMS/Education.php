<?php

namespace App\Models\HRMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $table = 'education';
    protected $fillable = ['institute', 'degree', 'passing_year', 'result', 'cgpa'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
