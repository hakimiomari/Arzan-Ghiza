<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPassword extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old_password' => ['required'],
            'password' => 'required|min:3',
            'confirm_new_password' => 'required|same:password|min:3',
        ];
    }
}
