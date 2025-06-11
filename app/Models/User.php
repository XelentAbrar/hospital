<?php

namespace App\Models;

use App\Models\HRMS\DoctorCharge;
use App\Models\HRMS\Employee;
use XelentAbrar\HospitalExpense\Models\Expenses\Expense;
use App\Models\IPD\Admission;
use App\Models\Lab\PatientTest;
use App\Models\OPD\Appointment;
use XelentAbrar\HospitalDonation\Models\Donation\DonorFund;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected $with = [
        'userRole',
    ];
    public function userRole(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    function doctorCharge(): HasOne
    {
        return $this->hasOne(DoctorCharge::class);
    }

    function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
    public function roles()
{
    return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
}
public function createdAppointments()
{
    return $this->hasMany(Appointment::class, 'created_by');
}

public function createdAdmissions()
{
    return $this->hasMany(Admission::class, 'created_by');
}

public function updatedAdmissions()
{
    return $this->hasMany(Admission::class, 'updated_by');
}

public function createdPatientTests()
{
    return $this->hasMany(PatientTest::class, 'created_by');
}

public function createdDonorFunds()
{
    if(file_exists(base_path('config/donation.php'))) {
        return $this->hasMany(DonorFund::class, 'created_by');
    }
}

public function createdExpenses()
{
    if(file_exists(base_path('config/expense.php'))) {
        return $this->hasMany(Expense::class, 'created_by');
    }
}
}
