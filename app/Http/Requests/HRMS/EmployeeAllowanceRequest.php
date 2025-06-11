<?php

namespace App\Http\Requests\HRMS;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeAllowanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'allowance_id' => 'required|exists:allowances,id',
            'amount' => 'required|numeric|min:0',
            'effective_date' => 'required|date',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'employee_id' => 'employee',
            'allowance_id' => 'allowance',
        ];
    }
}
