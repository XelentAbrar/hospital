<?php

namespace App\Models\OPD;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\IPD\Admission;
use App\Models\OPD\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnic',
        'dob',
        'gender',
        'relation_name',
        'relation_type',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'phone',
        'mr_number',
        'email',
        'blood_group',
        'rh_factor',
        'age',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function appointments():hasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id' ,'id');
    }
    public function admissions(): hasMany
    {
        return $this->hasMany(Admission::class, 'patient_id','id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
