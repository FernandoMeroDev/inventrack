<?php

namespace Database\Factories\Receipts;

use App\Models\Products\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipts\Movement>
 */
class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $max_products_id = Product::orderBy('id', 'desc')->first()->id;
        return [
            'amount' => fake()->randomNumber(2),
            'existences' => fake()->randomNumber(4),
            'product_id' => fake()->numberBetween(1, $max_products_id),
        ];
    }
}
