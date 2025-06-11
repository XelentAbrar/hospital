<?php

namespace App\Http\Requests\HRMS;

use App\Models\HRMS\Designation;
use App\Models\HRMS\Employee;
use Illuminate\Foundation\Http\FormRequest;

class EmployeePromotionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'employee_id' => 'required|exists:'. Employee::class .',id',
            'from_designation_id' => 'required|exists:'. Designation::class .',id',
            'to_designation_id' => 'required|exists:'. Designation::class .',id',
            'promotion_date' => 'required|date',
            'reason' => 'nullable',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'employee_id' => 'employee',
            'from_designation_id' => 'from designation',
            'to_designation_id' => 'to designation',
        ];
    }
}
