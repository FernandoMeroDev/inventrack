<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    private array $warehouses = [
        'Depósito',
        'Licoreria'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->warehouses as $name){
            Warehouse::create(['name' => $name]);
        }
    }
}
