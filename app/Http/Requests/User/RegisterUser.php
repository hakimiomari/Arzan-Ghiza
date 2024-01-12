<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
    //  * @return bool
    //  */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'name' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:200|unique:users,email',
            'phone' => 'required|min:10|max:10|regex:/^[0-9]{10}$/',
            'city' => 'required|string|min:3|regex:/^[a-zA-Z\s]+$/',
            'district' => 'required|string|min:3',
            'valige' => 'required|string|min:3',
            'password' => 'required|min:3',
            'confirmPassword' => 'required|same:password|min:3',
        ];
    }
}
