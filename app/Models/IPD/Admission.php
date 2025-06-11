<?php

namespace App\Models\IPD;

use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use App\Models\User;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\HRMS\Department;
use App\Models\OT\Operation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'Ipd_serial',
        'file_no',
        'admission_date',
        'admission_time',
        'discharge_date',
        'discharge_time',
        'reason_for_admission',
        'room_bed_id',
        'bed_id',
        'cnic',
        'address',
        'no_of_days',
        'day_charges',
        'relation_name',
        'dob',
        'phone',
        'gender',
        'status',
        'department_id',
        'is_printed',
        'is_discharged',
        'type',
        'refered_by',
        'advance_amount',
        'received_amount',
        'donor_fee',
        'careoff_id',
        'zf_fee',
        'welfare',
        'slip_no',
        'discharge_slip_no',
        'discount_amount',
        'chart_no',
        'refund_amount',
        'total_amount',
        'cancel',
        'age','created_by','updated_by'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function roomBed(): BelongsTo
    {
        return $this->belongsTo(RoomBed::class, 'bed_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_bed_id','id');
    }

    public function room_type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'type');
    }

    public function details(): HasMany
    {
        return $this->hasMany(AdmissionDetail::class);
    }


    public function careoff(): BelongsTo
    {
        if(file_exists(base_path('config/donation.php'))) {
            return $this->belongsTo(Donor::class, 'careoff_id');
        }
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function operation(): HasOne
    {
        return $this->hasOne(Operation::class, 'file_no', 'file_no');
    }
    public function voucherAuditDetails()
    {
        return $this->hasMany(VoucherAuditDetail::class, 'transaction_no', 'id');
    }


}
