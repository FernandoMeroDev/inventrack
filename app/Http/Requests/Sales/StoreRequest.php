<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'comment' => 'nullable|string|max:500',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|integer|exists:products,id',
            'amounts' => 'required|array|min:1',
            'amounts.*' => 'required|integer|min:1|max:65000',
            'sale_price_ids' => 'required|array|min:1',
            'sale_price_ids.*' => 'required|integer|exists:sale_prices,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'warehouse_id' => 'bodega',
            'comment' => 'comentario',
            'product_ids' => 'productos',
            'product_ids.*' => 'producto #:position',
            'amounts' => 'cantidades',
            'amounts.*' => 'cantidad #:position',
            'sale_price_ids' => 'precios de venta',
            'sale_price_ids.*' => 'precio de venta #:position'
        ];
    }
}
