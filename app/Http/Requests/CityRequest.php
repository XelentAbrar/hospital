<?php

namespace App\Http\Requests;

use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'state_id' => 'required|exists:' . State::class . ',id',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'country_id' => 'country',
            'state_id' => 'state',
        ];
    }
}
