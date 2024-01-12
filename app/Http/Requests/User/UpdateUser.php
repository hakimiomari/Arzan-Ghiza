<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:200|email',
            'phone' => 'required|min:10|max:10|regex:/^[0-9]{10}$/',
            'city' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
            'district' => 'required|string|min:3',
            'valige' => 'required|string|min:3',
        ];
    }
}
