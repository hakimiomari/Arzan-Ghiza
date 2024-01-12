<?php

namespace App\Http\Requests\BusinessUser;

use Illuminate\Foundation\Http\FormRequest;

class LoginBUserRequest extends FormRequest
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
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string', 'min:3']
        ];
    }
}
