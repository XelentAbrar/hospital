<?php

namespace App\Models\OPD;

use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HRMS\Employee;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'type',
        'is_printed',
        'consulting_doctor_id',
        'appointment_date',
        'is_doctor_checked',
        'appointment_time',
        "patient_name",
        "patient_cnic",
        "patient_dob",
        "patient_gender",
        "patient_relation_name",
        "patient_relation_type",
        "patient_address",
        "patient_city_id",
        "patient_state_id",
        "patient_country_id",
        "patient_phone",
        "patient_email",
        "patient_blood_group",
        "patient_rh_factor",
        "careoff_id",
        "zf_id",
        "file_no",
        "donor_fee",
        "welfare",
        "doc_serial",
        "zf_fee",
        "slip_no",
        "patient_age",
        'cancel','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointmentDetails(): HasMany
    {
        return $this->hasMany(AppointmentDetail::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'consulting_doctor_id', 'id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function careoff(): BelongsTo
    {
        if(file_exists(base_path('config/donation.php'))) {
            return $this->belongsTo(Donor::class, 'careoff_id');
        }
    }

    public function zf(): BelongsTo
    {
        if(file_exists(base_path('config/donation.php'))) {
            return $this->belongsTo(Donor::class, 'zf_id');
        }
    }
    public function voucherAuditDetails()
    {
    return $this->hasMany(VoucherAuditDetail::class, 'transaction_no', 'id');
    }

}
