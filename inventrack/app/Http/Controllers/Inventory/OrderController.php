<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\OrderRequest;
use App\Models\Products\Product;
use App\Models\Shelves\LevelProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use stdClass;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        $validated = $request->validated();
        $products = $this->query();
        $products = $this->filterandOrderProducts($products, $validated);

        $total_count = $products->count();
        $products = $this->simplePaginate(
            $products, 10, $validated['page'] ?? 1, $request->url()
        )->withQueryString()->fragment('products');

        $outsidePageRange = $products->count() < 1 && $total_count > 0;
        if($outsidePageRange)
            return $this->resetOrderPage($validated);

        return view('entities.inventory.order', [
            'products' => $products,
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

    private function resetOrderPage(array $inputs)
    {
        $inputs['page'] = 1;
        return redirect()->route('inventory.order', $inputs);
    }

    private function query(): Collection
    {
        $products = Product::orderBy('name')->get();
        foreach($products as $product){
            $warehouses_inventory = [];
            $product->existences = 0;
            $product->min_stock = 0;
            foreach($product->warehouses as $warehouse){
                $warehouse_inventory = new stdClass();
                // Set warehouse_inventory attributes
                $warehouse_inventory->id = $warehouse->id;
                $warehouse_inventory->name = $warehouse->name;
                $warehouse_inventory->min_stock = $warehouse->pivot->min_stock;
                $levelProduct = LevelProduct::
                    join('levels', 'levels.id', '=', 'level_product.level_id')
                    ->join('shelves', 'levels.shelf_id', '=', 'shelves.id')
                    ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
                    ->select('level_product.id', 'level_product.amount')
                    ->where('level_product.product_id', $product->id)
                    ->where('warehouses.id', $warehouse->id)
                    ->get();
                $warehouse_inventory->existences = $levelProduct->sum('amount');

                // Set product attributes
                $product->existences += $warehouse_inventory->existences;
                $product->min_stock += $warehouse_inventory->min_stock;

                // Set derivated attributes
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

            // Change warehouse algorithm
            $move = 0;
            $move_from = '';
            $move_to = '';

            $d = $product->warehouses_inventory[0]->existences;
            $md = $product->warehouses_inventory[0]->min_stock;
            $l = $product->warehouses_inventory[1]->existences;
            $ml = $product->warehouses_inventory[1]->min_stock;
            if($d < $md && $l <= $ml){
                if($d == 0 && $l > 0){
                    $move = ($l - ($l % 2)) / 2;
                    $move_from = 'Licorería';
                    $move_to = 'Depósito';
                }
            }
            if($d <= $md && $l < $ml){
                if($d > 0 && $l == 0){
                    $move = ($d - ($d % 2)) / 2;
                    $move_from = 'Depósito';
                    $move_to = 'Licorería';
                }
            }
            if($d > $md && $l < $ml){
                $remain = $d - $md;
                $lack = $ml - $l;
                if($l == 0){
                    $move_from = 'Depósito';
                    $move_to = 'Licorería';
                    if(($lack - $remain) < 0){
                        $move = $lack;
                    } else if(($lack - $remain) == 0){
                        $move = $remain;
                    } else if(($lack - $remain) > 0) {
                        $move = $remain;
                        $half = ($md - ($md % 2)) / 2;
                        if(($lack - $remain - $half) < 0){
                            $excess = -($lack - $remain - $half);
                            $move += $half - $excess;
                        } else {
                            $move += $half;
                        }
                    }
                }
            }
            if($d < $md && $l > $ml){
                $remain = $l - $ml;
                $lack = $md - $d;
                if($d == 0){
                    $move_from = 'Licorería';
                    $move_to = 'Depósito';
                    if(($lack - $remain) < 0){
                        $move = $lack;
                    } else if(($lack - $remain) == 0){
                        $move = $remain;
                    } else if(($lack - $remain) > 0) {
                        $move = $remain;
                        $half = ($md - ($md % 2)) / 2;
                        if(($lack - $remain - $half) < 0){
                            $excess = -($lack - $remain - $half);
                            $move += $half - $excess;
                        } else {
                            $move += $half;
                        }
                    }
                }
            }

            $product->move = $move;
            $product->move_from = $move_from;
            $product->move_to = $move_to;
        }
        return $products;
    }

    private function filterAndOrderProducts(Collection $products, array $validated): Collection
    {
        $products = $products->filter(function (Product $product, int $key) use ($validated) {
            return $product->lack > 0 || $product->move > 0;
        });
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
