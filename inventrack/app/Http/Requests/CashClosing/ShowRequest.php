<?php

namespace App\Http\Requests\CashClosing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowRequest extends FormRequest
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
        $date = date('Y-m-d');
        $aMonthAgoDate = date('Y-m-d', mktime(date('H'), month: date('n') - 1));
        return [
            'user_id' => $user_id_rules,
            'warehouse_id' => 'required|integer|exists:warehouses,id',
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
            'page' => 'sometimes|integer|min:1',
            'search' => 'sometimes|nullable|string|max:255',
            'order_by' => [
                'sometimes', 'nullable', 'string',
                Rule::in(['name', 'amount', 'value'])
            ],
            'order' => ['sometimes', 'nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'usuario',
            'warehouse_id' => 'bodega',
            'initial_date' => 'fecha inicial',
            'end_date' => 'fecha final',
            'search' => 'buscar',
            'order_by' => 'ordernar por',
            'order' => 'orden'
        ];
    }
}
