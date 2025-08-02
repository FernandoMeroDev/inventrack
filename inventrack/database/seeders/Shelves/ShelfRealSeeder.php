<?php

namespace Database\Seeders\Shelves;

use App\Models\Shelves\Level;
use App\Models\Shelves\Shelf;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ShelfRealSeeder extends Seeder
{
    // shelf_number => shelf_levels
    private array $depositShelves = [
        1 => 5,
        2 => 4,
        3 => 4, 
        4 => 3, 
        5 => 4, 
        6 => 4, 
        7 => 5, 
        8 => 4, 
        9 => 4, 
        10 => 4,
    ];

    // shelf_number => shelf_levels
    private array $liquorStoreShelves = [
        1 => 3,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createShelves(
            $this->depositShelves,
            Warehouse::where('name', 'Depósito')->first(),
            refrigerators: []
        );
        $this->createShelves(
            $this->liquorStoreShelves,
            Warehouse::where('name', 'Licoreria')->first(),
            refrigerators: []
        );
    }

    private function createShelves(array $shelves, Warehouse $warehouse, array $refrigerators): void
    {
        foreach($shelves as $number => $levels){
            $shelf = Shelf::create([
                'number' => $number,
                'warehouse_id' => $warehouse->id,
                'refrigerator' => Arr::exists(array_flip($refrigerators), $number)
            ]);
            for($i = 0; $i <= $levels; $i++){
                Level::create([
                    'number' => $i,
                    'shelf_id' => $shelf->id
                ]);
            }
        }
    }
}
