<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->get('user_id') === 'all'){
            $user_id_rules = 'required|string|size:3';
        } else {
            $user_id_rules = 'required|integer|exists:users,id';
        }
        if($this->get('warehouse_id') === 'all'){
            $warehouse_id_rules = 'required|string|size:3';
        } else {
            $warehouse_id_rules = 'required|integer|exists:warehouses,id';
        }
        $date = date('Y-m-d');
        $aMonthAgoDate = date('Y-m-d', mktime(date('H'), month: date('n') - 1));
        return [
            'type_id' => 'required|integer|exists:receipt_types,id',
            'user_id' => $user_id_rules,
            'warehouse_id' => $warehouse_id_rules,
            'initial_date' => [
                "required", "date_format:Y-m-d",
                "after_or_equal:$aMonthAgoDate",
                "before_or_equal:end_date"
            ],
            'end_date' => [
                "required", "date_format:Y-m-d",
                "after_or_equal:initial_date",
                "before_or_equal:$date"
            ],
            'page' => 'sometimes|integer|min:1'
        ];
    }

    public function attributes(): array
    {
        return [
            'type_id' => 'tipo de comprobante',
            'user_id' => 'usuario',
            'warehouse_id' => 'bodega',
            'initial_date' => 'fecha inicial',
            'end_date' => 'fecha final',
        ];
    }
}
