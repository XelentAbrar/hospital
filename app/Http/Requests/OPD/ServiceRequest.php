<?php

namespace App\Http\Requests\OPD;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use App\Models\OPD\Category;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:IPD,OPD,Other',
            'charges' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:' . Category::class . ',id',
            'description' => 'nullable|string',
            'is_nursing' => 'nullable',
            'is_advance' => 'nullable',
        ];
        if(file_exists(base_path('config/accounts.php'))) {
            $rules['coa_id'] = 'required|exists:'. ChartOfAccount::class .',id';
        }

        return $rules;
    }
}
