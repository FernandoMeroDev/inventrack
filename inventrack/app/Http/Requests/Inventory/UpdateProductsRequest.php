<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_ids' => 'array',
            'amounts' => 'array',
            'product_ids.*' => 'integer|exists:products,id',
            'amounts.*' => 'integer|min:1|max:255'
        ];
    }
}
