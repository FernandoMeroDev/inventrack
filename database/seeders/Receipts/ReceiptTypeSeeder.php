<?php

namespace Database\Seeders\Receipts;

use App\Models\Receipts\ReceiptType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptTypeSeeder extends Seeder
{
    private array $types = [
        ['purchase', 'compra'],
        ['sale', 'venta'],
        ['retirement', 'retiro'],
        ['entrance', 'entrada'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->types as $type){
            ReceiptType::create(['name' => $type[0], 'label' => $type[1]]);
        }
    }
}
