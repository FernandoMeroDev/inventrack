<?php

namespace App\Http\Requests\Inventory\Shelves;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|integer|min:1|max:255',
            'levels' => 'required|array|min:2',
            'levels.*' => 'required|integer|min:0|max:255'
        ];
    }
}
