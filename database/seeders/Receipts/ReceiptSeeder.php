<?php

namespace Database\Seeders\Receipts;

use App\Models\Receipts\Receipt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Receipt::factory(10)->create();
    }
}
