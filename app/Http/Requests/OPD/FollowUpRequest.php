<?php

namespace App\Http\Requests\OPD;

use App\Models\OPD\Appointment;
use Illuminate\Foundation\Http\FormRequest;

class FollowUpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'appointment_id' => 'required|exists:' . Appointment::class . ',id',
            'follow_up_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string',
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
