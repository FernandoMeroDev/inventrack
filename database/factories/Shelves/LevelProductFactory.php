<?php

namespace Database\Factories\Shelves;

use App\Models\Products\Product;
use App\Models\Shelves\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shelves\LevelProduct>
 */
class LevelProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $max_level_id = Level::orderBy('id', 'desc')->first()->id;
        $max_product_id = Product::orderBy('id', 'desc')->first()->id;
        return [
            'product_id' => fake()->numberBetween(1, $max_product_id),
            'level_id' => fake()->numberBetween(1, $max_level_id),
            'amount' => fake()->numberBetween(1, 15),
        ];
    }
}
