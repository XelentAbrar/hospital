<?php

namespace App\Http\Requests\OPD;

use App\Models\OPD\Appointment;
use Illuminate\Foundation\Http\FormRequest;

class PatientFeedbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'appointment_id' => 'required|exists:' . Appointment::class . ',id',
            'feedback' => 'required|string',
        ];

        return $rules;
    }



    public function attributes()
    {
        return [
            'appointment_id' => 'appointment',
        ];
    }
}
