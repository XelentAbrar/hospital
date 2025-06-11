<?php

namespace App\Http\Requests\OPD;

use App\Models\City;
use App\Models\Country;
use App\Models\HRMS\Employee;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'patient_id' => 'nullable|exists:' . Patient::class . ',id',
            'type' => 'required|in:General,Specialist',
            'consulting_doctor_id' => 'required|exists:' . Employee::class . ',id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i:s',
            'patient_name' => 'required|string|max:255',
            'doc_serial' => 'nullable',
            'is_doctor_checked' => 'boolean',
            'patient_dob' => 'nullable|date|before_or_equal:today',
            'patient_gender' => 'required|in:Male,Female,Children',
            'patient_relation_name' => 'nullable|string|max:255',
            'patient_relation_type' => 'nullable|in:Guardian,Spouse,Parent,Sibling,Child,Friend,Relative',
            'file_no' => 'nullable|string',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'patient_address' => 'nullable|string',
            'patient_country_id' => 'nullable|exists:' . Country::class . ',id',
            'patient_state_id' => 'nullable|exists:' . State::class . ',id',
            'patient_city_id' => 'nullable|exists:' . City::class . ',id',
            'patient_phone' => 'nullable|string|max:255',
            'welfare' => 'nullable|string|max:255',
            'patient_email' => 'nullable|email|max:255',
            'patient_blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'patient_rh_factor' => 'nullable|string|max:255',
            'appointment_details' => 'required|array|min:1',
            'appointment_details.*.service.id' => 'required|exists:' . Service::class . ',id',
            'appointment_details.*.fee' => 'required|numeric',
            'appointment_details.*.fee_status.value' => 'nullable|in:Pending,Complete',
        ];

        if(file_exists(base_path('config/donation.php'))) {
            $rules['donor_fee'] = ['nullable|numeric'];
            $rules['zf_fee'] = ['nullable|numeric'];
            $rules['careoff_id'] = ['nullable|exists:' . Donor::class . ',id'];
            $rules['zf_id'] = ['nullable|exists:' . Donor::class . ',id'];
        }

        if ($this->patient_cnic == '' && $this->patient_phone == '') {
            $rules['patient_cnic'] = ['required', 'string', 'max:255'];
        }
        else{
            $rules['patient_cnic'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }



    public function attributes()
    {
        return [
            'patient_id' => 'patient',
            'consulting_doctor_id' => 'consulting doctor',
            'country_id' => 'country name',
            'state_id' => 'state name',
            'city_id' => 'city name',
            'dob' => 'date of birth',
            'appointment_details.*.service.id' => 'service',
            'appointment_details.*.fee' => 'fee',
            'appointment_details.*.fee_status.value' => 'fee status',
        ];
    }
}
