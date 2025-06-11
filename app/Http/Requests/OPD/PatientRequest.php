<?php

namespace App\Http\Requests\OPD;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|max:255|unique:patients',
            'dob' => 'nullable|date|before_or_equal:today',
            // 'pat ient_age' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Children',
            'relation_name' => 'nullable|string|max:255',
            'mr_number' => 'nullable',
            'relation_type' => 'nullable|in:Guardian,Spouse,Parent,Sibling,Child,Friend,Relative',
            'address' => 'nullable|string|max:255',
            'age' => 'nullable|string|max:255',
            'country_id' => 'nullable|exists:' . Country::class . ',id',
            'state_id' => 'nullable|exists:' . State::class . ',id',
            'city_id' => 'nullable|exists:' . City::class . ',id',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'rh_factor' => 'nullable|string|max:255',
        ];

        return $rules;
    }



    public function attributes()
    {
        return [
            'country_id' => 'country name',
            'state_id' => 'state name',
            'city_id' => 'city name',
            'dob' => 'date of birth',
        ];
    }
}
