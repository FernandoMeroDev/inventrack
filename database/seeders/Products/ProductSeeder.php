<?php

namespace Database\Seeders\Products;

use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private int $products_count = 10,
                $sale_prices_count = 3;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory($this->products_count)
            ->has(SalePrice::factory(
                $this->sale_prices_count * 2,
                new Sequence(...$this->salePriceSequence())
            ))->create();
    }

    private function salePriceSequence(): array
    {
        $sequence = [];
        for($i = 0; $i < $this->sale_prices_count; $i++){
            $sequence[] = ['units_number' => $i + 1];
            $sequence[] = ['units_number' => $i + 1];
        }
        return $sequence;
    }
}
