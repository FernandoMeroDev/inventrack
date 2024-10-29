<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\Products\ProductSeeder;
use Database\Seeders\Receipts\ReceiptSeeder;
use Database\Seeders\Receipts\ReceiptTypeSeeder;
use Database\Seeders\Shelves\ShelfSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private bool $create_real_data = false;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if($this->create_real_data){
            $this->createRealData();
        } else {
            $this->createFakeData();
        }
    }

    private function createFakeData(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->call([
            ProductSeeder::class,
            ReceiptTypeSeeder::class,
        ]);

        Warehouse::factory(5)->create();

        $this->call([
            ReceiptSeeder::class,
            ShelfSeeder::class,
        ]);
    }

    private function createRealData(): void
    {
        $this->call([
            ReceiptTypeSeeder::class,
            WarehouseSeeder::class,
        ]);
    }
}
