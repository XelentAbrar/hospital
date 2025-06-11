<?php

namespace App\Http\Requests\IPD;

use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use App\Models\IPD\Room;
use App\Models\IPD\RoomBed;
use App\Models\IPD\RoomType;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'patient_id' => 'nullable|exists:' . Patient::class . ',id',
            'name' => 'required|string|max:255',
            'file_no' => 'nullable|string',
            'admission_date' => 'required|date|before_or_equal:today',
            'admission_time' => 'required',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'reason_for_admission' => 'nullable|string',
            'discharge_time' => 'nullable',
            'room_bed_id' => 'required|exists:' . Room::class . ',id',
            'bed_id' => 'required|exists:' . RoomBed::class . ',id',
            'address' => 'nullable|string|max:255',
            'relation_name' => 'nullable|string|max:255',
            'dob' => 'nullable|date|before_or_equal:today',
            'phone' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Children',
            'status' => 'required|in:Pending,Complete',
            'Ipd_serial' => 'nullable',
            'age' => 'required',
            // 'no_of_days' => 'nullable|numeric',
            // 'day_charges' => 'nullable',
            'total_amount' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->status === 'Complete' && $value != 0) {
                        $fail('The ' . $attribute . ' must be 0 when the status is Complete.');
                    }
                },
            ],
            'discount_amount' => 'nullable|numeric',
            'refund_amount' => 'nullable|numeric',
            'chart_no' => 'nullable|string',
            'welfare' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:' . Department::class . ',id',
            'type' => 'nullable|exists:' . RoomType::class . ',id',
            'advance_amount' => 'nullable|numeric',
            'refered_by' => 'nullable|string|max:255',
            'details.*.service.id' => 'nullable|exists:' . Service::class . ',id',
            'details.*.doctor.id' => 'nullable|exists:' . Employee::class . ',id',
            'details.*.date' => 'required|date',
            'details.*.desc' => 'nullable|string',
            'details.*.amount' => 'nullable|numeric',
            'details.*.is_doctor' => 'nullable|boolean',
            'details.*.no_of_days' => 'nullable|numeric',
            'details.*.day_charges' => 'nullable|numeric',
            'details.*.service_amount' => 'nullable|numeric',
        ];
        if(file_exists(base_path('config/donation.php'))) {
            $rules['donor_fee'] = ['nullable|numeric'];
            $rules['zf_fee'] = ['nullable|numeric'];
            $rules['careoff_id'] = ['nullable|exists:' . Donor::class . ',id'];
        }

        if ($this->cnic == '' && $this->phone == '') {
            $rules['cnic'] = ['required', 'string', 'max:255'];
        }
        else{
            $rules['cnic'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'patient_id' => 'patient',
            'room_bed_id' => 'room bed',
            'department_id' => 'department',
            'dob' => 'date of birth',
            'details.*.service.id' => 'service',
            'details.*.doctor.id' => 'doctor',
            'details.*.date' => 'date',
            'details.*.desc' => 'description',
            'details.*.amount' => 'doctor amount',
            'details.*.service_amount' => 'service amount',
            'details.*.no_of_days' => 'days',
        ];
    }
}
