<?php

namespace App\Http\Requests;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class StateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'abrv' => 'required|string|max:255',
            'country_id' => 'required|exists:' . Country::class . ',id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'country_id' => 'country',
        ];
    }
}
