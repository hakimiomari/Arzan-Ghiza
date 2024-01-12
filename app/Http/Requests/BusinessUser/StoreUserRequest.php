<?php

namespace App\Http\Requests\BusinessUser;

use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:bussinesses'],
            'password' => ['required', 'string', 'min:3'],
            'confirmPassword' => ['required', 'same:password'],
            'phone' => 'required|min:10|max:10|regex:/^[0-9]{10}$/',
            'city' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
            'district' => 'required|string',
            'valige' => 'required|string',
            'bussiness_name' => 'required|string|min:3',
        ];
    }
}
