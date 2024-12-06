<?php

namespace App\Http\Requests\Products;

use App\Models\Warehouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $warehouses = Warehouse::all();
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')->ignore($product->id)
            ],
            'image' => 'nullable|file|image|max:50',
            'remove_image' => 'sometimes|accepted',
            'min_stocks' => [
                'required',
                'array:'.$warehouses->pluck('id')->join(','),
                'size:'.$warehouses->count()
            ],
            'min_stocks.*' => 'required|integer|min:0|max:255',
            'units_numbers' => 'required|array|min:1|max:20',
            'units_numbers.*' => 'required|integer|min:1|max:255',
            'prices' => 'required|array|min:1|max:20',
            'prices.*' => 'decimal:0,6|min:0.000001|max:9999.999999|distinct:strict',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'image' => 'imagen',
            'remove_image' => 'remover imagen',
            'min_stocks' => 'stocks mínimos',
            'min_stocks.*' => 'stock mínimo #:position',
            'units_numbers' => 'numeros de unidades',
            'units_numbers.*' => 'numero de unidad #:position',
            'prices' => 'precios',
            'prices.*' => 'precio #:position',
        ];
    }
}
