<?php

namespace App\Http\Controllers\CashClosing;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashClosing\ShowRequest;
use App\Models\Products\Product;
use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;

class ShowController extends Controller
{
    public function ask()
    {
        return view('entities.cash-closing.ask');
    }

    public function show(ShowRequest $request)
    {
        $validated = $request->validated();
        $products = $this->query($validated);
        $products = $this->orderProducts($products, $validated);
        $inputs = $validated;
        if($validated['user_id'] === 'all'){
            $inputs['user_name'] = 'Todos';
        } else {
            $inputs['user_name'] = User::find($validated['user_id'])->name;
        }
        $inputs['warehouse_name'] = Warehouse::find($validated['warehouse_id'])->name;
        $inputs['total'] = '$' . number_format($products->sum('value'), 2, ',', ' ');
        $total_count = $products->count();
        $products = $this->simplePaginate(
            $products, 10, $validated['page'] ?? 1, $request->url()
        )->withQueryString()->fragment('products');
        if($products->count() < 1 && $total_count > 0)
            return $this->resetPage($validated);
        return view('entities.cash-closing.show', [
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

    private function resetPage(array $inputs)
    {
        $inputs['page'] = 1;
        return redirect()->route('cash-closing.show', $inputs);
    }

    private function query(array $validated): Collection
    {
        $products = Product::join('movements', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->select('products.id', 'products.name')
            ->where('receipts.issuance_date', '>=', $validated['initial_date'] . ' 00:00:00')
            ->where('receipts.issuance_date', '<=', $validated['end_date'] . ' 23:59:59')
            ->where('receipts.warehouse_id', $validated['warehouse_id'])
            ->where('receipts.type_id', ReceiptType::where('name', 'sale')->first()->id);
        if(isset($validated['search'])){
            $products = $products->where('products.name', 'LIKE', '%' . $validated['search'] . '%');
        }
        if($validated['user_id'] !== 'all'){
            $products = $products->where('receipts.user_id', $validated['user_id']);
        }
        $products = $products->groupBy('id')->get();
        foreach($products as $product){
            $movements = $this->queryMovements($product, $validated);
            $receipts = $this->queryReceipts($product, $validated);
            $product->amount = $movements->sum('amount');
            $product->value = $movements->sum('price');
            $product->receipts = $receipts;
        }
        return $products;
    }

    private function orderProducts(Collection $products, array $validated): Collection
    {
        if(isset($validated['order_by'])){
            $products = $products->sortBy(
                $validated['order_by'], descending: ($validated['order'] ?? null) === 'desc'
            );
        }
        return $products;
    }

    private function queryReceipts(Product $product, array $validated): Collection
    {
        $receipts = Receipt::join('movements', 'movements.receipt_id', '=', 'receipts.id')
            ->select('receipts.id')
            ->where('movements.product_id', $product->id)
            ->where('receipts.created_at', '>=', $validated['initial_date'] . ' 00:00:00')
            ->where('receipts.created_at', '<=', $validated['end_date'] . ' 23:59:59')
            ->where('receipts.warehouse_id', $validated['warehouse_id'])
            ->where('receipts.type_id', ReceiptType::where('name', 'sale')->first()->id);
        if($validated['user_id'] !== 'all'){
            $receipts = $receipts->where('receipts.user_id', $validated['user_id']);
        }
        return $receipts->groupBy('id')->get();
    }

    private function queryMovements(Product $product, array $validated): Collection
    {
        $movements = Movement::join('products', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->select('movements.id', 'movements.amount', 'movements.price')
            ->where('movements.product_id', $product->id)
            ->where('receipts.created_at', '>=', $validated['initial_date'] . ' 00:00:00')
            ->where('receipts.created_at', '<=', $validated['end_date'] . ' 23:59:59')
            ->where('receipts.warehouse_id', $validated['warehouse_id'])
            ->where('receipts.type_id', ReceiptType::where('name', 'sale')->first()->id);
        if($validated['user_id'] !== 'all'){
            $movements = $movements->where('receipts.user_id', $validated['user_id']);
        }
        return $movements->get();
    }
}
