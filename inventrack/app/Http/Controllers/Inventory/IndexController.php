<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\IndexRequest;
use App\Models\Products\Product;
use App\Models\Shelves\LevelProduct;
use App\Models\Warehouse;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use stdClass;

class IndexController extends Controller
{
    public function download()
    {
        $products = $this->queryAllWarehouses([
            "type" => "physical",
            "warehouse_id" => "all"
        ]);
        $text = "Producto,Depósito,Licorería\n";
        foreach($products as $product){
            $text .= $product->name . ',';
            foreach($product->warehouses_inventory as $warehouse){
                $text .= $warehouse->existences . ',';
            }
            $text .= $product->existences . ',';
            $text .= "\n";
        }
        $filename = ( new DateTime() )->format("Y-m-d_H_i_s u") . '.csv'; 
        Storage::disk('local')->put($filename, $text);
        return response()->download(storage_path("app/private/$filename"), "reporte-$filename");
    }

    public function ask()
    {
        return view('entities.inventory.ask');
    }

    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $inputs = [
            'type' => $validated['type'],
            'warehouse' => Warehouse::find($validated['warehouse_id']) ?? 'all'
        ];

        $products = $validated['warehouse_id'] == 'all'
            ? $this->queryAllWarehouses($validated)
            : $this->querySpecificWarehouse($validated);
        $products = $this->filterandOrderProducts($products, $validated);
        $total_count = $products->count();
        $products = $this->simplePaginate(
            $products, 10, $validated['page'] ?? 1, $request->url()
        )->withQueryString()->fragment('products');

        $outsidePageRange = $products->count() < 1 && $total_count > 0;
        if($outsidePageRange)
            return $this->resetIndexPage($validated);

        return view('entities.inventory.index', [
            'products' => $products,
            'inputs' => $inputs,
            'filters' => [
                'any' => isset($validated['search'])
                    || isset($validated['order_by'])
                    || isset($validated['order']),
                'search' => $validated['search'] ?? null,
                'order_by' => $validated['order_by'] ?? null,
                'order' => $validated['order'] ?? null,
            ]
        ]);
    }

    private function queryAllWarehouses(array $validated): Collection
    {
        $products = Product::orderBy('name')->get();
        foreach($products as $product){
            $warehouses_inventory = [];
            $product->existences = 0;
            $product->min_stock = 0;
            foreach($product->warehouses as $warehouse){
                $warehouse_inventory = new stdClass();
                $warehouse_inventory->id = $warehouse->id;
                $warehouse_inventory->name = $warehouse->name;
                $warehouse_inventory->min_stock = $warehouse->pivot->min_stock;
                if($validated['type'] === 'virtual'){
                    // Virtual
                    $warehouse_inventory->existences = $product->remainIn($warehouse->id);
                } else {
                    // Physical
                    $levelProduct = LevelProduct::
                        join('levels', 'levels.id', '=', 'level_product.level_id')
                        ->join('shelves', 'levels.shelf_id', '=', 'shelves.id')
                        ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
                        ->select('level_product.id', 'level_product.amount')
                        ->where('level_product.product_id', $product->id)
                        ->where('warehouses.id', $warehouse->id)
                        ->get();
                    $warehouse_inventory->existences = $levelProduct->sum('amount');
                }
                $product->existences += $warehouse_inventory->existences;
                $product->min_stock += $warehouse_inventory->min_stock;
                $warehouse_inventory->lack = $warehouse_inventory->min_stock - $warehouse_inventory->existences;
                $warehouse_inventory->lack = $warehouse_inventory->lack > 0 ? $warehouse_inventory->lack : 0;
                $warehouse_inventory->remain = $warehouse_inventory->existences - $warehouse_inventory->min_stock;
                $warehouse_inventory->remain = $warehouse_inventory->remain > 0 ? $warehouse_inventory->remain : 0;
                $warehouses_inventory[] = $warehouse_inventory;
            }
            $product->warehouses_inventory = $warehouses_inventory;
            $product->lack = $product->min_stock - $product->existences;
            $product->lack = $product->lack > 0 ? $product->lack : 0;
            $product->remain = $product->existences - $product->min_stock;
            $product->remain = $product->remain > 0 ? $product->remain : 0;
        }
        return $products;
    }

    private function querySpecificWarehouse(array $validated): Collection
    {
        return $validated['type'] === 'virtual'
            ? $this->queryVirtual($validated)
            : $this->queryPhysical($validated);
    }

    private function resetIndexPage(array $inputs)
    {
        $inputs['page'] = 1;
        return redirect()->route('inventory.index', $inputs);
    }

    private function queryVirtual(array $validated): Collection
    {
        $products = Product::orderBy('name')->get();
        foreach($products as $product){
            $product->existences = $product->remainIn($validated['warehouse_id']);
            $this->setDerivatedAttributes($product, $validated['warehouse_id']);
        }
        return $products;
    }

    private function queryPhysical(array $validated): Collection
    {
        $products = Product::orderBy('name')->get();
        foreach($products as $product){
            $levelProduct = LevelProduct::
                join('levels', 'levels.id', '=', 'level_product.level_id')
                ->join('shelves', 'levels.shelf_id', '=', 'shelves.id')
                ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
                ->select('level_product.id', 'level_product.amount')
                ->where('level_product.product_id', $product->id)
                ->where('warehouses.id', $validated['warehouse_id'])
                ->get();
            $product->existences = $levelProduct->sum('amount');
            $this->setDerivatedAttributes($product, $validated['warehouse_id']);
        }
        return $products;
    }

    private function setDerivatedAttributes(Product &$product, int $warehouse_id): void
    {
        $product->min_stock = $product->warehouses()
            ->wherePivot('warehouse_id', $warehouse_id)
            ->first()->pivot->min_stock;
        $product->lack = $product->min_stock - $product->existences;
        $product->lack = $product->lack > 0 ? $product->lack : 0;
        $product->remain = $product->existences - $product->min_stock;
        $product->remain = $product->remain > 0 ? $product->remain : 0;
    }

    private function filterAndOrderProducts(Collection $products, array $validated): Collection
    {
        if(isset($validated['search'])){
            $products = $products->filter(function (Product $product, int $key) use ($validated) {
                return str_contains($product->name, mb_strtoupper($validated['search']));
            });
        }
        if(isset($validated['order_by'])){
            $products = $products->sortBy(
                $validated['order_by'], descending: ($validated['order'] ?? null) === 'desc'
            );
        }
        return $products;
    }
}
