<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_number' => 'required',
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required|integer|min:1',
            'brand_id' => 'required|exists:brands,id',
            'isbn' => 'required|integer'
        ];
    }
}
