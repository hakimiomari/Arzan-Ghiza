<?php

namespace App\Http\Requests\Product;

use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'price' => ['required', 'regex:/^[1-9][0-9]*(\.[0-9]+)?$/'],
            'discount_price' => ['required','lt:price', 'regex:/^(?:0|[1-9]\d*)(?:\.\d+)?$/'],
            'quantity' => ['required', 'regex:/^[1-9][0-9]*$/'],
            'expires_date' => 'required|after:today',
            'description' => 'required|string|min:25',
            'image' => 'required|mimes:png,jpg,jpeg,webp,gif,svg,avi'

        ];
    }
}
