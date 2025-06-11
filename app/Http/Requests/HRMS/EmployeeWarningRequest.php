<?php

namespace App\Http\Requests\HRMS;

use App\Models\User;
use App\Models\HRMS\Employee;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeWarningRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'employee_id' => 'required|exists:' . Employee::class . ',id',
            'warning_reason' => 'required|string',
            'warning_type' => 'required|in:verbal,written,suspension,other',
            'additional_notes' => 'nullable|string',
            'warning_date' => 'required|date',
            'issued_by' => 'nullable|exists:' . User::class . ',id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'employee_id' => 'employee',
        ];
    }
}
