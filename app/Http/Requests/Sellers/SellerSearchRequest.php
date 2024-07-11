<?php

namespace App\Http\Requests\Sellers;

use Illuminate\Foundation\Http\FormRequest;

class SellerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'city_id' => 'nullable|integer',
            'address' => 'nullable',
            'city_name' => 'nullable|string|max:255',
            'state_name' => 'nullable|string|max:255',
            'per_page' => 'integer',
            'page' => 'integer'
        ];
    }
}
