<?php

namespace App\Http\Requests\HRMS;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use App\Models\City;
use App\Models\Country;
use App\Models\HRMS\Department;
use App\Models\HRMS\Designation;
use App\Models\HRMS\Employee;
use App\Models\HRMS\Grade;
use App\Models\Role;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'machine_id' => 'required|integer',
            'serial_no' => 'nullable',
            'email' => 'nullable|email|unique:' . Employee::class . ',email,' . $this->id,
            'about_employee' => 'nullable|string',
            'image' => 'nullable|string',
            'mobile' => 'nullable|string',
            'landline' => 'nullable|string',
            'street_address' => 'nullable|string|max:255',
            'postal_code' => 'nullable',
            'country_id' => 'nullable|exists:' . Country::class . ',id',
            'state_id' => 'nullable|exists:' . State::class . ',id',
            'city_id' => 'nullable|exists:' . City::class . ',id',
            'dob' => 'nullable|date|before_or_equal:today',
            'doj' => 'nullable|date|before_or_equal:today',
            'probation_start_date' => 'nullable|date',
            'probation_end_date' => 'nullable|date',
            'notice_period_start_date' => 'nullable|date',
            'notice_period_end_date' => 'nullable|date',
            'reporting_to' => 'nullable|exists:' . Employee::class . ',id',
            'slack_id' => 'nullable|string|max:255',
            'department_id' => 'required|exists:' . Department::class . ',id',
            'designation_id' => 'required|exists:' . Designation::class . ',id',
            'grade_id' => 'required|exists:' . Grade::class . ',id',
            'employee_type' => 'nullable|in:Full Time,Part Time,On Contract,Internship,Trainee',
            'marital_status' => 'nullable|in:Unmarried,Married,Divorced,Widowed',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender' => 'nullable|in:Male,Female,Others',
            'religion' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_location' => 'nullable|string|max:255',
            'tax_payer_id' => 'nullable|string|max:255',
            'basic_salary' => 'nullable',
            'salary_currency' => 'nullable|in:PKR,USD',
            'education' => 'nullable|array',
            'education.*.institute' => 'nullable|string|max:255',
            'education.*.degree' => 'nullable|string|max:255',
            'education.*.passing_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'education.*.result' => 'nullable',
            'education.*.cgpa' => 'nullable|numeric|between:0,4',
            'experience' => 'nullable|array',
            'experience.*.company' => 'nullable|string|max:255',
            'experience.*.designation' => 'nullable|string|max:255',
            'experience.*.job_period' => 'nullable|string|max:255',
            'experience.*.responsibility' => 'nullable|string|max:255',
            'experience.*.skills' => 'nullable|string|max:255',
            'login_allowed' => 'nullable|boolean',
        ];

        if (isset($this->is_doctor) && $this->is_doctor == true) {
            $rules['visiting_charges'] = ['nullable', 'numeric', 'min:0'];
            $rules['consulting_charges'] = ['nullable', 'numeric', 'min:0'];
            $rules['doctor_template'] = ['required'];
            $rules['shares'] = ['nullable', 'numeric', 'min:0'];
            $rules['shares_type'] = ['nullable', 'in:Percentage,Fixed'];
            $rules['doctor_type'] = ['required', 'in:General,Specialist'];
            if(file_exists(base_path('config/accounts.php'))) {
                $rules['coa_id'] = ['required', 'exists:' . ChartOfAccount::class . ',id'];
            }
        }
        if (isset($this->login_allowed) && $this->login_allowed == true) {
            $rules['password'] = ['required', 'string', new Password, 'confirmed'];
            $rules['roles'] = ['required', 'array'];
            $rules['roles.*'] = ['required', 'exists:' . Role::class . ',id'];
            if (isset($this->employee)) {
                if (isset($this->password)) {
                    $rules['password'] = ['required', 'string', new Password, 'confirmed'];
                } else {
                    $rules['password'] = ['nullable', 'string', new Password, 'confirmed'];
                }
            }
        }

        return $rules;
    }


    public function attributes()
    {
        return [
            'country_id' => 'country name',
            'state_id' => 'state name',
            'city_id' => 'city name',
            'dob' => 'date of birth',
            'doj' => 'date of joining',
            'department_id' => 'department',
            'designation_id' => 'designation',
            'grade_id' => 'grade',
            'education.*.institute' => 'institute',
            'education.*.degree' => 'degree',
            'education.*.passing_year' => 'passing year',
            'education.*.result' => 'result',
            'education.*.cgpa' => 'cgpa',
            'experience.*.company' => 'company',
            'experience.*.designation' => 'designation',
            'experience.*.job_period' => 'job period',
            'experience.*.responsibility' => 'responsibility',
            'experience.*.skills' => 'skills',
            'roles.*' => 'roles',
        ];
    }
}
