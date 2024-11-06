<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashClosing\ShowRequest;
use App\Models\Products\Product;
use App\Models\Receipts\Movement;
use App\Models\Receipts\Receipt;
use App\Models\Receipts\ReceiptType;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Collection;

class CashClosingController extends Controller
{
    public function ask()
    {
        return view('entities.cash-closing.ask');
    }

    public function show(ShowRequest $request)
    {
        $validated = $request->validated();
        $products = $this->query($validated);
        if($validated['user_id'] === 'all'){
            $validated['user_name'] = 'Todos';
        } else {
            $validated['user_name'] = User::find($validated['user_id'])->name;
        }
        $validated['warehouse_name'] = Warehouse::find($validated['warehouse_id'])->name;
        $validated['total'] = '$' . number_format($products->sum('value'), 2, ',', ' ');
        $products = $this->simplePaginate(
            $products, 2, $validated['page'] ?? 1, $request->url()
        )->withQueryString()->fragment('products');
        return view('entities.cash-closing.show', [
            'products' => $products,
            'data' => $validated
        ]);
    }

    private function query(array $validated): Collection
    {
        $soldProducts = Product::join('movements', 'movements.product_id', '=', 'products.id')
            ->join('receipts', 'movements.receipt_id', '=', 'receipts.id')
            ->select('products.id', 'products.name')
            ->where('receipts.created_at', '>=', $validated['initial_date'] . ' 00:00:00')
            ->where('receipts.created_at', '<=', $validated['end_date'] . ' 23:59:59')
            ->where('receipts.warehouse_id', $validated['warehouse_id'])
            ->where('receipts.type_id', ReceiptType::where('name', 'sale')->first()->id);
        if($validated['user_id'] !== 'all'){
            $soldProducts = $soldProducts->where('receipts.user_id', $validated['user_id']);
        }
        $soldProducts = $soldProducts->groupBy('id')->get();
        $products = collect([]);
        foreach($soldProducts as $product){
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
            $movements = $movements->get();
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
            $receipts = $receipts->groupBy('id')->get();
            $products->push([
                'id' => $product->id,
                'name' => $product->name,
                'amount' => $movements->sum('amount'),
                'value' => $movements->sum('price'),
                'receipts' => $receipts
            ]);
        }
        return $products;
    }
}