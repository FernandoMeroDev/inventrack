<?php

namespace Database\Seeders\Products;

use App\Models\Products\Product;
use App\Models\Products\ProductWarehouse;
use App\Models\Products\SalePrice;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductRealSeeder extends Seeder
{
    private $filepath = "\seeders\Products\products-data.csv";

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path().$this->filepath, "r");

        if ($file !== false) {
            while (($row = fgetcsv($file)) !== false) {
                $this->create($row[0], $row[1]);
            }
            if($row !== false) fclose($row);
        } else {
            dump("No se pudo abrir el archivo.");
        }
    }

    private function create($name, $purchase_price)
    {
        $warehouses = Warehouse::all();
        $product = Product::create([
            'name' => $name,
            'image_uploaded' => false,
            'purchase_price' => $purchase_price
        ]);
        SalePrice::create([
            'units_number' => 1,
            'value' => 0.01,
            'product_id' => $product->id,
        ]);
        foreach($warehouses as $warehouse){
            ProductWarehouse::create([
                'min_stock' => 0,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
            ]);
        }
    }
}
