<?php

namespace App\Http\Requests\IPD;

use App\Models\IPD\Room;
use App\Models\OPD\Patient;
use Illuminate\Foundation\Http\FormRequest;

class PatientCaseStudyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'patient_id' => 'required|exists:' . Patient::class . ',id',
            'food_allergies' => 'nullable|string',
            'tendency_bleed' => 'nullable|string',
            'heart_disease' => 'nullable|string',
            'high_blood_pressure' => 'nullable|string',
            'diabetic' => 'nullable|string',
            'surgery' => 'nullable|string',
            'accident' => 'nullable|string',
            'family_medical_history' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'female_pregnancy' => 'nullable|string',
            'breast_feeding' => 'nullable|string',
            'health_insurance' => 'nullable|string',
            'notes' => 'nullable|string',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'patient_id' => 'patient',
        ];
    }
}
