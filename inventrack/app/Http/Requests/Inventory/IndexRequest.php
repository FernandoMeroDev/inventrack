<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->get('warehouse_id') === 'all'){
            $warehouse_id_rules = 'required|string|size:3';
        } else {
            $warehouse_id_rules = 'required|integer|exists:warehouses,id';
        }
        return [
            'type' => ['required', 'string', Rule::in(['virtual', 'physical'])],
            'warehouse_id' => $warehouse_id_rules,
            'page' => 'sometimes|integer|min:1',
            'search' => 'sometimes|nullable|string|max:255',
            'order_by' => [
                'sometimes', 'nullable', 'string',
                Rule::in(['name', 'existences', 'min_stock', 'lack', 'remain'])
            ],
            'order' => ['sometimes', 'nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'tipo',
            'warehouse_id' => 'bodega',
            'page' => 'página',
            'search' => 'buscar',
            'order_by' => 'ordernar por',
            'order' => 'orden'
        ];
    }
}
