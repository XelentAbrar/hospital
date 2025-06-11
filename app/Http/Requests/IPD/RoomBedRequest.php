<?php

namespace App\Http\Requests\IPD;

use App\Models\IPD\Room;
use Illuminate\Foundation\Http\FormRequest;

class RoomBedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'room_id' => 'required|exists:' . Room::class . ',id',
            'bed_number' => 'required|string|max:255',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'room_id' => 'room',
        ];
    }
}
