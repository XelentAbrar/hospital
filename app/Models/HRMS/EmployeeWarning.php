<?php

namespace App\Models\HRMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeWarning extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'warning_reason',
        'warning_type',
        'additional_notes',
        'warning_date',
        'issued_by',
    ];

    // protected $with = ['issuedBy'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // EmployeeWarning.php

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

}
