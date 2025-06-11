<?php

namespace App\Http\Requests\HRMS;

use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use App\Models\HRMS\RosterType;
use App\Models\HRMS\Shift;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeShiftRosterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'employee' => 'required|array|min:1',
            'employee.*.employee_id' => 'required|exists:' . Employee::class . ',id',
            'employee.*.department_id' => 'required|exists:' . Department::class . ',id',
            'employee.*.roster_type_id' => 'required|exists:' . RosterType::class . ',id',
            'employee.*.rest_day' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'shift_id' => 'required|exists:' . Shift::class . ',id',
            'working_days' => 'required|array',
            'working_days.*' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'employee' => 'employee',
            'employee.*.employee_id' => 'employee',
            'employee.*.department_id' => 'department',
            'employee.*.roster_type_id' => 'roster type',
            'employee.*.rest_day' => 'rest day',
            'working_days' => 'working days',
            'working_days.*' => 'working days',
        ];
    }
}
