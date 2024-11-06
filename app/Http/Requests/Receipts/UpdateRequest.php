<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $receipt = $this->route('receipt');
        return $receipt->type->name === 'sale'
            && !$receipt->consolidated;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comment' => 'nullable|string|max:500',
            'movement_ids' => 'required|array|min:1',
            'movement_ids.*' => 'required|integer|exists:movements,id',
            'amounts' => 'required|array|min:1',
            'amounts.*' => 'required|integer|min:0|max:65000',
            'sale_price_ids' => 'required|array|min:1',
            'sale_price_ids.*' => 'required|integer|exists:sale_prices,id'
        ];
    }

    public function attributes(): array
    {
        return [
            'comment' => 'comentario',
            'movement_ids' => 'movimientos',
            'movement_ids.*' => 'movimiento #:position',
            'amounts' => 'cantidades',
            'amounts.*' => 'cantidad #:position',
            'sale_price_ids' => 'precios de venta',
            'sale_price_ids.*' => 'precio de venta #:position'
        ];
    }
}
