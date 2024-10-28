<?php

namespace Database\Seeders\Receipts;

use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    private int $receipts_count = 10,
                $movements_count = 5;

    private ReceiptType $sale;

    public function __construct()
    {
        $this->sale = ReceiptType::where('name', 'sale')->first();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Receipt::factory($this->receipts_count)->create();
        foreach(Receipt::all() as $receipt){
            $factory = Movement::factory($this->movements_count);
            $state = ['receipt_id' => $receipt->id];
            if($receipt->type->id == $this->sale->id){
                $state['price'] = fake()->randomFloat(6, 0.01, 9999.99);
            }
            $factory->state($state)->create();
        }
    }
}
