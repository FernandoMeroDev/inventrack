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
        1 => 4,
        2 => 1,
        3 => 1, 
        4 => 5, 
        5 => 5, 
        6 => 5, 
        7 => 5, 
        8 => 4, 
        9 => 4, 
        10 => 4, 
        11 => 4, 
        12 => 4,
        13 => 4,
        14 => 4,
        15 => 4,
        16 => 4,
        17 => 5,
        18 => 4,
        19 => 4,
        20 => 4,
        21 => 4,
        22 => 4,
        23 => 4,
        24 => 4,
        25 => 4,
        26 => 4,
        27 => 4,
        28 => 4,
    ];

    // shelf_number => shelf_levels
    private array $liquorStoreShelves = [
        1 => 3,
        2 => 3,
        3 => 5, 
        4 => 5, 
        5 => 4, 
        6 => 4, 
        7 => 5, 
        8 => 5, 
        9 => 4, 
        10 => 4, 
        11 => 4, 
        12 => 5
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createShelves(
            $this->depositShelves,
            Warehouse::where('name', 'Depósito')->first(),
            refrigerators: [2, 3, 4, 5, 6, 7, 8, 23, 24, 27, 28]
        );
        $this->createShelves(
            $this->liquorStoreShelves,
            Warehouse::where('name', 'Licoreria')->first(),
            refrigerators: [3, 4, 7, 8]
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
