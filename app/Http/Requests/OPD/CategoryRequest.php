<?php

namespace App\Http\Requests\OPD;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ];
        if(file_exists(base_path('config/accounts.php'))) {
            $rules['coa_id'] = 'required|exists:'. ChartOfAccount::class .',id';
        }

        return $rules;
    }
}
