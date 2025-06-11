<?php

namespace App\Http\Requests\HRMS;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceMachineRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'location' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
        ];

        return $rules;
    }
}
