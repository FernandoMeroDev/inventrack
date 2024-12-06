<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\IndexRequest;
use App\Models\Products\Product;
use App\Models\Shelves\LevelProduct;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;

class IndexController extends Controller
{
    public function ask()
    {
        return view('entities.inventory.ask');
    }

    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $inputs = [
            'type' => $validated['type'],
            'warehouse' => Warehouse::find($validated['warehouse_id'])
        ];
    
        $products = $validated['type'] === 'virtual'
            ? $this->queryVirtual($validated)
            : $this->queryPhysical($validated);
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
