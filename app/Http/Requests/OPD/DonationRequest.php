<?php

namespace App\Http\Requests\OPD;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'donor' => 'required',
            'charges' => 'required|numeric|min:0',
            'account_on' => 'nullable',
            'donor_serial' => 'nullable',
            'donor_date' => 'nullable|date|before_or_equal:today',
            'narration' => 'nullable',
            'donor_time' => 'nullable|date_format:H:i',
        ];

        return $rules;
    }
}
