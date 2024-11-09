<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Audit\AskAuditRequest;
use App\Models\Products\Product;
use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use App\Models\Shelves\LevelProduct;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function askAudit(AskAuditRequest $request, Warehouse $warehouse)
    {
        $validated = $request->validated();
        $products = $this->queryProducts($warehouse);
        $products = $this->filterAndOrderProducts($products, $validated);
        $total_count = $products->count();
        $products = $this->simplePaginate(
            $products, 5, $request->get('page', 1), $request->url()
        )->withQueryString()->fragment('products');
        $unconsolidated_count = $this->queryUnconsolidatedIn($warehouse)->count();

        $outsidePageRange = $products->count() < 1 && $total_count > 0;
        if($outsidePageRange)
            return $this->resetPage($validated);

        return view('entities.inventory.audit.ask', [
            'warehouse' => $warehouse,
            'products' => $products,
            'anyDiscrepancy' => $total_count > 0,
            'unconsolidated_count' => $unconsolidated_count,
            'filters' => [
                'any' => isset($validated['search'])
                    || isset($validated['order_by'])
                    || isset($validated['order']),
                'search' => $validated['search'] ?? null,
                'order_by' => $validated['order_by'] ?? null,
                'order' => $validated['order'] ?? null,
            ],
        ]);
    }

    private function resetPage(array $inputs)
    {
        $inputs['page'] = 1;
        return redirect()->route('inventory.ask-audit', $inputs);
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

    private function queryProducts(Warehouse $warehouse): Collection
    {
        $products = Product::orderBy('name')->get();
        foreach($products as $product){
            $product->virtualExistences = $product->remainIn($warehouse->id);
            $levelProduct = LevelProduct::
                join('levels', 'levels.id', '=', 'level_product.level_id')
                ->join('shelves', 'levels.shelf_id', '=', 'shelves.id')
                ->join('warehouses', 'shelves.warehouse_id', '=', 'warehouses.id')
                ->select('level_product.id', 'level_product.amount')
                ->where('level_product.product_id', $product->id)
                ->where('warehouses.id', $warehouse->id)
                ->get();
            $product->physicalExistences = $levelProduct->sum('amount');
            $product->difference = $product->virtualExistences - $product->physicalExistences;
        }
        $products = $products->filter(function (Product $product, int $key) {
            return $product->difference !== 0;
        });
        return $products;
    }

    private function queryUnconsolidatedIn(Warehouse $warehouse)
    {
        return Receipt::where('warehouse_id', $warehouse->id)
            ->where('consolidated', false)
            ->get();
    }

    public function audit(Warehouse $warehouse)
    {
        $products = $this->queryProducts($warehouse);
        foreach($products as $product){
            if($product->difference > 0){
                $this->createRetirement(
                    $product->difference,
                    $warehouse,
                    $product
                );
            } else {
                $this->createEntrance(
                    -$product->difference,
                    $warehouse,
                    $product
                );
            }
        }
        $unconsolidatedSales = $this->queryUnconsolidatedIn($warehouse);
        foreach($unconsolidatedSales as $unconsolidatedSale){
            $unconsolidatedSale->consolidated = true;
            $unconsolidatedSale->save();
        }
        return redirect()->route('inventory.ask-audit', $warehouse->id);
    }

    private function createRetirement(int $amount, Warehouse $warehouse, Product $product)
    {
        $validated = [
            'warehouse_id' => $warehouse->id,
            'comment' => 'Calibracion de inventario',
            'product_id' => $product->id,
            'amount' => $amount,
        ];
        $receipt = $this->storeReceipt('retirement', $validated);
        $this->storeRetirementMovement([
            'amount' => $validated['amount'],
            'product_id' => $validated['product_id'],
            'receipt_id' => $receipt->id,
            'warehouse_id' => $validated['warehouse_id'],
        ]);
    }

    private function storeRetirementMovement(array $data): void
    {
        $data['price'] = 0.00;
        $existences = Product::find($data['product_id'])
            ->lastMovementIn($data['warehouse_id'])
            ->existences;
        $data['existences'] = $existences - $data['amount'];
        Movement::create($data);
    }
    
    private function createEntrance(int $amount, Warehouse $warehouse, Product $product)
    {
        $validated = [
            'warehouse_id' => $warehouse->id,
            'comment' => 'Calibracion de inventario',
            'product_id' => $product->id,
            'amount' => $amount,
        ];
        $receipt = $this->storeReceipt('entrance', $validated);
        $this->storeEntranceMovement([
            'amount' => $validated['amount'],
            'product_id' => $validated['product_id'],
            'receipt_id' => $receipt->id,
            'warehouse_id' => $validated['warehouse_id'],
        ]);
        return redirect()->route('purchases.create');
    }

    private function storeEntranceMovement(array $data): void
    {
        $existences = Product::find($data['product_id'])
            ->lastMovementIn($data['warehouse_id'])
            ?->existences ?? 0;
        $data['existences'] = $existences + $data['amount'];
        Movement::create($data);
    }

    private function storeReceipt(string $type_name, array $validated): Receipt
    {
        return Receipt::create([
            'comment' => $validated['comment'],
            'type_id' => ReceiptType::where('name', $type_name)->first()->id,
            'warehouse_id' => $validated['warehouse_id'],
            'user_id' => Auth::user()->id
        ]);
    }
}
