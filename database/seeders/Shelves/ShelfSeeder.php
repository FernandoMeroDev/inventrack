<?php

namespace Database\Seeders\Shelves;

use App\Models\Products\Product;
use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use App\Models\Shelves\Shelf;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    private int $shelves_per_warehouse_count = 10;

    private int $levels_per_shelf_count = 4;

    private int $level_product_count = 300;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();
        foreach($warehouses as $warehouse){
            Shelf::factory($this->shelves_per_warehouse_count)->state(new Sequence(
                ...$this->shelfSequence($warehouse->id)
            ))->has(
                Level::factory(4)->state(...$this->levelSequence())
            )->create();
        }
        LevelProduct::factory($this->level_product_count)->create();
    }

    private function shelfSequence($warehouse_id): array
    {
        $sequence = [];
        for($i = 0; $i < $this->shelves_per_warehouse_count; $i++){
            $sequence[] = ['name' => 'A' . ($i + 1), 'warehouse_id' => $warehouse_id];
        }
        return $sequence;
    }

    private function levelSequence(): array
    {
        $sequence = [];
        for($i = 0; $i < $this->levels_per_shelf_count; $i++){
            $sequence[] = ['number' => ($i + 1)];
        }
        return $sequence;
    }
}
