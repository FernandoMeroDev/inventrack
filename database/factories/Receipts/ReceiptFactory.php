<?php

namespace Database\Factories\Receipts;

use App\Models\Receipts\ReceiptType;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipts\Receipt>
 */
class ReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $max_users_id = User::orderBy('id', 'desc')->first()->id;
        $max_warehouses_id = Warehouse::orderBy('id', 'desc')->first()->id;
        $max_types_id = ReceiptType::orderBy('id', 'desc')->first()->id;
        $type_id = fake()->numberBetween(1, $max_types_id);
        $sale = ReceiptType::where('name', 'sale')->first();
        return [
            'comment' => fake()->boolean(30) ? fake()->paragraph() : null,
            'consolidated' => $type_id == $sale->id ? true : null,
            'type_id' => $type_id,
            'warehouse_id' => fake()->numberBetween(1, $max_warehouses_id),
            'user_id' => fake()->numberBetween(1, $max_users_id),
        ];
    }
}
