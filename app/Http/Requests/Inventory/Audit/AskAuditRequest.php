<?php

namespace App\Http\Requests\Inventory\Audit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AskAuditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'search' => 'sometimes|nullable|string|max:255',
            'order_by' => [
                'sometimes', 'nullable', 'string',
                Rule::in(['name', 'virtualExistences', 'physicalExistences', 'difference'])
            ],
            'order' => ['sometimes', 'nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
