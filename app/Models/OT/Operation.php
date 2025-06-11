<?php

namespace App\Models\OT;

use App\Models\HRMS\Department;
use App\Models\IPD\Room;
use App\Models\IPD\RoomBed;
use App\Models\IPD\RoomType;
use App\Models\OPD\Donor;
use App\Models\OPD\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Operation extends Model
{
    use HasFactory;
    protected $fillable = [
       'operation_date', 
       'file_no', 
       'ot_number', 
       'patient_name', 
       'patient_age', 
       'patient_phone', 
       'patient_address', 
       'patient_cnic', 
       'patient_gender', 
       'patient_relation_name', 
       'surgeon_name', 
       'anasthetic_doc_name', 
       'status', 
       'is_hepatitus', 
       'anasthesia_type', 
       'procedure_type', 
       'lap_chole', 
       'start_time', 
       'end_time', 
       'advance_amount', 
       'received_amount', 
       'donor_fee', 
       'zf_fee', 
       'welfare', 
       'referred_by', 
       'assistant_male', 
       'assistant_female', 
       'is_printed',
       'department_id',
       'bed_id',
       'type_id',
       'room_bed_id',
       'careoff_id',
       'patient_id',
       'created_by',
       'updated_by'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OperationDetail::class);
    }
    public function careoff(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'careoff_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
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
        return $this->belongsTo(Room::class, 'room_bed_id');
    }

    public function room_type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'type');
    }
}
