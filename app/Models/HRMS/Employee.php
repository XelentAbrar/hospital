<?php

namespace App\Models\HRMS;

use Carbon\Carbon;
use App\Models\User;
use App\Models\State;
use App\Models\OPD\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','serial_no' ,'email', 'password', 'machine_id','mobile', 'landline', 'street_address', 'postal_code', 'doj', 'dob', 'probation_start_date', 'probation_end_date', 'notice_period_start_date', 'notice_period_end_date', 'gender', 'reporting_to', 'image', 'login_allowed', 'slack_id', 'about_employee', 'religion', 'emergency_contact', 'skills', 'employee_type', 'marital_status', 'blood_group', 'account_name', 'account_number', 'bank_name', 'bank_location', 'tax_payer_id', 'basic_salary', 'salary_currency', 'country_id', 'state_id', 'city_id', 'department_id', 'designation_id', 'grade_id','shares','shares_type','doctor_type','doctor_template'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Add coa_id to fillable if accounts config exists
        if (file_exists(base_path('config/accounts.php'))) {
            $this->fillable[] = 'coa_id';
        }
    }
    
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($employee) {
            if (Carbon::now()->startOfYear()->isToday()) {
                self::query()->update(['serial_no' => 0]);
            }
        });
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    public function doctorCharges()
    {
        return $this->hasOne(DoctorCharge::class, 'employee_id', 'id');
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function experience()
    {
        return $this->hasMany(Experience::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function allowances()
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowance')
            ->withPivot('amount', 'effective_date')
            ->withTimestamps();
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function warnings()
    {
        return $this->hasMany(EmployeeWarning::class);
    }

    public function attendances()
    {
        return $this->hasMany(DailyAttendance::class);
    }

    public function dailyAttendances()
    {
        return $this->hasMany(DailyAttendance::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'consulting_doctor_id');
    }
}
