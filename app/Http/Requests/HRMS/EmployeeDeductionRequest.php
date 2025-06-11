<?php

namespace App\Http\Requests\HRMS;

use App\Models\HRMS\Employee;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeDeductionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'employee_id' => 'required|exists:' . Employee::class . ',id',
            'deduction_type' => 'required|in:tax,social_security,healthcare,savings,donations,other',
            'deduction_nature' => 'required|in:tax,single,recurring',
            'recurring_type' => 'required_if:deduction_nature,recurring|nullable|string',
            'amount' => 'required|numeric|min:0',
            'deduction_month' => 'required|date',
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
