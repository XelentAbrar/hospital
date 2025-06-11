<?php

namespace App\Http\Requests\HRMS;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
        ];
        if(file_exists(base_path('config/accounts.php'))) {
            $rules['coa_id'] = 'nullable|exists:'. ChartOfAccount::class .',id';
        }

        return $rules;
    }
}
