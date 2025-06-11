<?php

namespace App\Http\Requests\OT;

use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use App\Models\IPD\Room;
use App\Models\IPD\RoomBed;
use App\Models\IPD\RoomType;
use XelentAbrar\HospitalDonation\Models\Donation\Donor;
use App\Models\OPD\Patient;
use App\Models\OPD\Service;
use App\Models\OT\Operation;
use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules()
    {
        $rules = [
            // 'careoff_id' => 'nullable|exists:' . Operation::class . ',id',
            'patient_id' => 'nullable|exists:' . Patient::class . ',id',
            // 'room_bed_id' => 'required|exists:' . Room::class . ',id',
            // 'bed_id' => 'required|exists:' . RoomBed::class . ',id',#
            // 'type_id' => 'nullable|exists:' . RoomType::class . ',id',
            'department_id' => 'nullable|exists:' . Department::class . ',id',
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'nullable|string|max:255',
            'file_no' => 'nullable|string',
            'ot_number' => 'nullable|string',
            'patient_age' => 'nullable|string|max:255',
            'patient_gender' => 'required|in:Male,Female,Children',
            'patient_relation_name' => 'nullable|string|max:255',
            'surgeon_name' => 'nullable|string|max:255',
            'anasthetic_doc_name' => 'nullable|string|max:255',
            // 'anasthesia_type' => 'nullable',
            'anasthesia_type' => 'nullable|in:General Anesthesia,Spinal,Local',
            'procedure_type' => 'nullable',
            'lap_chole' => 'nullable',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'is_hepatitus' => 'nullable|boolean',
            'advance_amount' => 'nullable|numeric',
            'received_amount' => 'nullable|numeric',
            'patient_address' => 'nullable|string',
            'operation_date' => 'nullable',
            'referred_by' => 'nullable|string|max:255',
            'assistant_male' => 'nullable|string|max:255',
            'assistant_female' => 'nullable|string|max:255',
            'donor_fee' => 'nullable|numeric',
            'zf_fee' => 'nullable|numeric',
            'welfare' => 'nullable|string|max:255',
            'status' => 'nullable|in:Pending,Complete',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',

            // Operation detail fields
            'details.*.service.id' => 'nullable|exists:' . Service::class . ',id',
            'details.*.doctor.id' => 'nullable|exists:' . Employee::class . ',id',

            'details.*.date' => 'nullable|date',
            'details.*.description' => 'nullable|string',
            'details.*.doctor_amount' => 'nullable|numeric',
            'details.*.is_doctor' => 'nullable|boolean',
            'details.*.service_amount' => 'nullable|numeric',
        ];

        if(file_exists(base_path('config/donation.php'))) {
            $rules['careoff_id'] = 'nullable|exists:' . Donor::class . ',id';
        }

        if ($this->patient_cnic == '' && $this->patient_phone == '') {
            $rules['patient_cnic'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['patient_cnic'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'patient_id' => 'patient',
            'room_bed_id' => 'room bed',
            'department_id' => 'department',
            'details.*.service.id' => 'service',
            'details.*.doctor.id' => 'doctor',
            'details.*.date' => 'date',
            'details.*.description' => 'description',
            'details.*.doctor_amount' => 'doctor amount',
            'details.*.service_amount' => 'service amount',
            'details.*.no_of_days' => 'days',
        ];
    }
}
