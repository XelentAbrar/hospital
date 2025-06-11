<?php

namespace App\Http\Requests\IPD;

use App\Models\IPD\RoomType;
use App\Models\Lab\TestGroup;
use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'room_type_id' => 'required|exists:' . RoomType::class . ',id',
            'room_number' => 'required|string|max:255',
            'charges' => 'required|numeric|min:0',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'room_type_id' => 'room type',
        ];
    }
}
